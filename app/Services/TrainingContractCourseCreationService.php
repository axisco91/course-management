<?php

namespace App\Services;

use App\Jobs\SyncCourseToMoodle;
use App\Models\Advisor;
use App\Models\Certification;
use App\Models\Company;
use App\Models\Course;
use App\Models\CourseType;
use App\Models\MoodleCourseTemplate;
use App\Models\Registration;
use App\Models\Teacher;
use App\Models\TrainingAction;
use App\Models\TrainingContract;
use App\Models\TrainingContractElement;
use App\Models\WebPlatform;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class TrainingContractCourseCreationService
{
    public function __construct(private MoodleProvisioningClient $moodleClient)
    {
    }

    public function create(int $elementId, int $mainCompanyId, array $data): array
    {
        $teacher = Teacher::where('id', $data['teacher_id'])
            ->FilterMainCompany($mainCompanyId)
            ->first();
        if (!$teacher) {
            throw new RuntimeException('El profesor no pertenece a la empresa.');
        }

        [$platform, $remoteCourse, $categoryId] = $this->resolveMoodleSelection($mainCompanyId, $data);

        $course = DB::transaction(function () use ($elementId, $mainCompanyId, $data, $platform, $remoteCourse, $categoryId) {
            $element = TrainingContractElement::where('id', $elementId)
                ->FilterMainCompany($mainCompanyId)
                ->lockForUpdate()
                ->first();
            if (!$element) {
                throw new RuntimeException('Elemento formativo no encontrado.');
            }
            if ($element->course_id !== null) {
                throw new \DomainException('Este elemento ya tiene un curso creado.');
            }
            if (!$element->beginning || !$element->end) {
                throw new RuntimeException('El elemento debe tener fechas de inicio y fin antes de crear el curso.');
            }

            $contract = TrainingContract::where('id', $element->training_contract_id)
                ->where('main_company_id', $mainCompanyId)
                ->first();
            if (!$contract) {
                throw new RuntimeException('Contrato formativo no encontrado.');
            }

            $trainingAction = $this->resolveTrainingAction($element, $mainCompanyId);
            if (!$trainingAction) {
                throw new RuntimeException('No se ha podido determinar la acción formativa.');
            }

            $courseType = CourseType::where('name', 'CFA')->first();
            if (!$courseType) {
                throw new RuntimeException('No existe el tipo de curso CFA.');
            }

            $nextCourse = Course::setName(null, $trainingAction->id, $mainCompanyId);
            $course = Course::createWithService([
                'name' => trim($trainingAction->formative_action.' - '.$trainingAction->name, ' -'),
                'training_action_id' => $trainingAction->id,
                'group' => $nextCourse['group'],
                'teacher_id' => $data['teacher_id'],
                'web_platform_id' => $platform?->id,
                'moodle_mode' => $data['moodle_mode'],
                'moodle_category_id' => $categoryId,
                'beginning' => $element->beginning,
                'end' => $element->end,
                'morning_schedule' => null,
                'afternoon_schedule' => null,
                'formation_center_id' => null,
                'delivery_center_id' => null,
                'course_observation' => null,
                'price' => 0,
                'nebrija' => 0,
                'monday' => 0,
                'tuesday' => 0,
                'wednesday' => 0,
                'thursday' => 0,
                'friday' => 0,
                'saturday' => 0,
                'sunday' => 0,
                'outsourced' => 0,
                'reactivated' => 0,
                'canceled' => 0,
                'course_type_id' => $courseType->id,
                'main_company_id' => $mainCompanyId,
            ]);

            $company = Company::find($contract->company_id);
            $advisor = $company?->advisor_id ? Advisor::find($company->advisor_id) : null;
            Registration::createWithService([
                'course_id' => $course->id,
                'company_id' => $contract->company_id,
                'student_id' => $contract->student_id,
                'advisor_id' => $company?->advisor_id,
                'collaborator_id' => $company?->collaborator_id ?: $advisor?->collaborator_id,
                'price' => 0,
                'profitability_id' => null,
                'is_bonus' => 0,
                'main_company_id' => $mainCompanyId,
            ]);

            $this->configureMoodle($course, $trainingAction, $platform, $remoteCourse, $data['moodle_mode']);
            $element->addCourse(['course_id' => $course->id, 'main_company_id' => $mainCompanyId]);

            return $course->fresh();
        });

        $queueError = $this->queueMoodleSync($course);

        return [
            'element' => $this->elementPayload($elementId, $mainCompanyId),
            'course' => Course::withCourseData($mainCompanyId)->where('courses.id', $course->id)->first(),
            'moodle_queue_error' => $queueError,
        ];
    }

    private function resolveMoodleSelection(int $mainCompanyId, array $data): array
    {
        if ($data['moodle_mode'] === 'disabled') {
            return [null, null, null];
        }

        $platform = WebPlatform::where('id', $data['web_platform_id'])
            ->where('main_company_id', $mainCompanyId)
            ->first();
        if (!$platform) {
            throw new RuntimeException('La plataforma Moodle no pertenece a la empresa.');
        }

        $remoteCourseId = (int) ($data['moodle_mode'] === 'manual'
            ? $data['moodle_course_id']
            : $data['moodle_source_course_id']);
        $remoteCourse = collect($this->moodleClient->courses($platform))->first(
            fn (array $course) => (int) $course['id'] === $remoteCourseId
        );
        if (!$remoteCourse) {
            throw new RuntimeException('El curso Moodle seleccionado no existe.');
        }
        if ($data['moodle_mode'] === 'automatic') {
            $this->moodleClient->assertProvisioningAvailable($platform);
            $this->moodleClient->assertCategoryExists($platform, (int) $data['moodle_category_id']);
        }

        return [
            $platform,
            $remoteCourse,
            $data['moodle_mode'] === 'automatic' ? (int) $data['moodle_category_id'] : null,
        ];
    }

    private function configureMoodle(
        Course $course,
        TrainingAction $trainingAction,
        ?WebPlatform $platform,
        ?array $remoteCourse,
        string $mode
    ): void {
        if ($mode === 'manual') {
            $course->update([
                'moodle_course_id' => (int) $remoteCourse['id'],
                'moodle_shortname' => (string) $remoteCourse['shortname'],
                'moodle_sync_status' => 'pending',
            ]);
        }

        if ($mode === 'automatic') {
            MoodleCourseTemplate::updateOrCreate(
                ['training_action_id' => $trainingAction->id, 'web_platform_id' => $platform->id],
                [
                    'moodle_course_id' => (int) $remoteCourse['id'],
                    'moodle_shortname' => (string) $remoteCourse['shortname'],
                    'moodle_fullname' => (string) $remoteCourse['fullname'],
                ]
            );
        }
    }

    private function queueMoodleSync(Course $course): ?string
    {
        if ($course->moodle_mode === 'disabled') {
            return null;
        }

        try {
            SyncCourseToMoodle::dispatch($course->id, 'create')->onQueue('moodle');
            return null;
        } catch (\Throwable $e) {
            $course->update([
                'moodle_sync_status' => 'error',
                'moodle_sync_error' => $e->getMessage(),
            ]);

            return $e->getMessage();
        }
    }

    private function resolveTrainingAction(TrainingContractElement $element, int $mainCompanyId): ?TrainingAction
    {
        if ($element->training_action_id) {
            return TrainingAction::where('id', $element->training_action_id)
                ->where('main_company_id', $mainCompanyId)
                ->first();
        }

        $certification = Certification::find($element->certification_id);
        if (!$certification) {
            return null;
        }

        $trainingAction = $certification->training_action_id
            ? TrainingAction::where('id', $certification->training_action_id)->where('main_company_id', $mainCompanyId)->first()
            : TrainingAction::where('name', $certification->name)->where('main_company_id', $mainCompanyId)->first();

        if (!$trainingAction) {
            $trainingAction = TrainingAction::create([
                'name' => $certification->name,
                'face_to_face_hours' => $certification->face_to_face_hours,
                'teletraining_hours' => $certification->teletraining_hours,
                'total_hours' => $certification->total_hours,
                'active' => 1,
                'specialty' => 0,
                'in_catalog' => 1,
                'main_company_id' => $mainCompanyId,
            ]);
        }

        $certification->update(['training_action_id' => $trainingAction->id]);
        $element->update(['training_action_id' => $trainingAction->id]);

        return $trainingAction;
    }

    private function elementPayload(int $elementId, int $mainCompanyId): ?TrainingContractElement
    {
        return TrainingContractElement::select(
            'training_contract_elements.*',
            'certifications.name as certification_name',
            'certifications.total_hours as certification_total_hours',
            'training_actions.formative_action',
            'training_actions.name as training_action_name',
            'training_actions.total_hours as training_action_total_hours'
        )
            ->leftJoin('training_actions', 'training_actions.id', '=', 'training_contract_elements.training_action_id')
            ->leftJoin('certifications', 'certifications.id', '=', 'training_contract_elements.certification_id')
            ->where('training_contract_elements.id', $elementId)
            ->FilterMainCompany($mainCompanyId)
            ->first();
    }
}
