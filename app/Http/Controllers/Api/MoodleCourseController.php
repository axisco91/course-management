<?php

namespace App\Http\Controllers\Api;

use App\Helpers\GeneralHelpers;
use App\Jobs\SyncCourseToMoodle;
use App\Models\Course;
use App\Models\MoodleCourseTemplate;
use App\Models\TrainingAction;
use App\Models\WebPlatform;
use App\Services\MoodleProvisioningClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RuntimeException;
use Illuminate\Contracts\Bus\Dispatcher;

class MoodleCourseController extends BaseController
{
    public function platformCourses(int $platformId, Request $request, MoodleProvisioningClient $client)
    {
        try {
            return $this->sendResponse(['courses' => $client->courses($this->platform($platformId, $request))], 'Cursos Moodle obtenidos.');
        } catch (RuntimeException $e) {
            return response()->json(['message' => $this->connectorError($e)], 422);
        }
    }

    public function diagnostics(int $platformId, Request $request, MoodleProvisioningClient $client)
    {
        try {
            return $this->sendResponse($client->siteInfo($this->platform($platformId, $request)), 'Conexión verificada.');
        } catch (RuntimeException $e) {
            return response()->json(['message' => $this->connectorError($e)], 422);
        }
    }

    public function saveTemplate(int $trainingActionId, Request $request, MoodleProvisioningClient $client)
    {
        $data = $request->validate(['web_platform_id' => ['required', 'integer'], 'moodle_course_id' => ['required', 'integer', 'min:1']]);
        $mainCompanyId = $this->mainCompanyId($request);
        TrainingAction::where('id', $trainingActionId)->where('main_company_id', $mainCompanyId)->firstOrFail();
        $platform = $this->platform((int) $data['web_platform_id'], $request);
        $source = collect($client->courses($platform))->firstWhere('id', (int) $data['moodle_course_id']);
        abort_unless($source, 422, 'El curso base no existe en la plataforma seleccionada.');
        $template = MoodleCourseTemplate::updateOrCreate(
            ['training_action_id' => $trainingActionId, 'web_platform_id' => $platform->id],
            ['moodle_course_id' => $source['id'], 'moodle_shortname' => $source['shortname'], 'moodle_fullname' => $source['fullname']]
        );
        return $this->sendResponse(['template' => $template], 'Curso base guardado.');
    }

    public function templates(int $trainingActionId, Request $request)
    {
        $mainCompanyId = $this->mainCompanyId($request);
        TrainingAction::where('id', $trainingActionId)->where('main_company_id', $mainCompanyId)->firstOrFail();
        return $this->sendResponse(['templates' => MoodleCourseTemplate::with('webPlatform:id,name')->where('training_action_id', $trainingActionId)->get()], 'Cursos base obtenidos.');
    }

    public function link(int $courseId, Request $request, MoodleProvisioningClient $client)
    {
        $data = $request->validate(['web_platform_id' => ['required', 'integer'], 'moodle_course_id' => ['required', 'integer', 'min:1']]);
        $course = $this->course($courseId, $request);
        $platform = $this->platform((int) $data['web_platform_id'], $request);
        $remote = collect($client->courses($platform))->firstWhere('id', (int) $data['moodle_course_id']);
        abort_unless($remote, 422, 'El curso Moodle seleccionado no existe.');
        $course->update([
            'web_platform_id' => $platform->id, 'moodle_mode' => 'manual', 'moodle_course_id' => $remote['id'],
            'moodle_shortname' => $remote['shortname'], 'moodle_sync_status' => 'pending', 'moodle_sync_error' => null,
        ]);
        SyncCourseToMoodle::dispatch($course->id, 'link')->onQueue('moodle');
        return $this->sendResponse(['course' => $course->fresh()], 'Curso Moodle vinculado.');
    }

    public function disconnect(int $courseId, Request $request)
    {
        $course = $this->course($courseId, $request);
        $course->update(['moodle_mode' => 'disabled', 'moodle_sync_status' => 'disconnected', 'moodle_sync_error' => null]);
        return $this->sendResponse(['course' => $course->fresh()], 'Curso desconectado sin modificar Moodle.');
    }

    public function sync(int $courseId, Request $request, Dispatcher $dispatcher)
    {
        $course = $this->course($courseId, $request);
        abort_if($course->moodle_mode === 'disabled', 422, 'El curso está desconectado de Moodle.');
        $course->update(['moodle_sync_status' => 'pending', 'moodle_sync_error' => null]);
        try {
            $dispatcher->dispatch((new SyncCourseToMoodle($course->id, 'retry'))->onQueue('moodle'));
            return $this->sendResponse(['course' => $course->fresh()], 'Sincronización encolada.');
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $course->fresh()->moodle_sync_error ?: $e->getMessage(),
                'course' => $course->fresh(),
            ], 422);
        }
    }

    private function mainCompanyId(Request $request): int
    {
        return (int) GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
    }

    private function platform(int $id, Request $request): WebPlatform
    {
        return WebPlatform::where('id', $id)->where('main_company_id', $this->mainCompanyId($request))->firstOrFail();
    }

    private function course(int $id, Request $request): Course
    {
        return Course::where('id', $id)->where('main_company_id', $this->mainCompanyId($request))->firstOrFail();
    }

    private function connectorError(RuntimeException $e): string
    {
        $message = mb_strtolower($e->getMessage());
        if (str_contains($message, 'external_functions')) {
            return 'La Moodle utiliza una versión anterior del puente local_zonaavz. Actualiza el plugin y ejecuta la actualización de Moodle.';
        }
        if (str_contains($message, 'control de acceso') || str_contains($message, 'access control')) {
            return 'El token no tiene autorizada esta función. Añade las funciones local_zonaavz al servicio externo utilizado por el token.';
        }

        return $e->getMessage();
    }
}
