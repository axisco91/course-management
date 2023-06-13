<?php

namespace App\Models;

use App\Helpers\GeneralHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Bill extends Model
{
	use HasFactory;

    public $timestamps = true;

    protected $table = 'billings';

    protected $fillable = ['course_id','company_id','number_students','billing','bonus','total_training_activity','expenses','only_organizing_entity','salary_costs','payment_id','communication_start_date','communication_end_date','invoiced','billing_number','billing_date','collection_date','bonus_status','company_bonus','observation', 'is_bonus', 'student_id', 'advisor_id', 'charged', 'collaborator_id', 'remitted'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function company()
    {
        return $this->hasOne('App\Models\Company', 'id', 'company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function course()
    {
        return $this->hasOne('App\Models\Course', 'id', 'course_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function payment()
    {
        return $this->hasOne('App\Models\Payment', 'id', 'payment_id');
    }

    public static function getBillings(){
        $cfa = CourseType::where('name', 'CFA')->first();
        $bills = Bill::select('billings.*',
            DB::raw("CONCAT(training_actions.formative_action,' / ', courses.group, ' ', training_actions.name) as course"),
            'training_actions.formative_action as training_action',
            'courses.group as group',
            'companies.name as company',
            'payments.name as payment',
            'advisors.name as advisor',
            'course_statuses.name as status',
            'courses.beginning as beginning',
            DB::raw("YEAR(courses.beginning) as year"),
            DB::raw("CONCAT(users.name,' ',users.surname) as collaborator"),
            DB::raw("(CASE WHEN billings.is_bonus='1' THEN 'Bonificada' ELSE 'No bonificada' END) as type"),
            DB::raw("(CASE WHEN billings.invoiced='1' THEN 'Si' ELSE 'No' END) as invoice"),
            DB::raw("(CASE WHEN billings.charged='1' THEN 'Si' ELSE 'No' END) as charge"))
            ->leftjoin('courses', 'courses.id', '=', 'billings.course_id')
            ->leftjoin('companies', 'companies.id', '=', 'billings.company_id')
            ->leftjoin('payments', 'payments.id', '=', 'billings.payment_id')
            ->leftjoin('students', 'students.id', '=', 'billings.student_id')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'courses.training_action_id')
            ->leftjoin('advisors', 'advisors.id', '=', 'billings.advisor_id')
            ->leftjoin('users', 'users.id', '=', 'billings.collaborator_id')
            ->leftjoin('course_statuses', 'course_statuses.id', '=', 'courses.course_status_id');

        if ($cfa) {
            $bills = $bills->where('course_type_id', '!=', $cfa->id);
        }
        $bills = $bills->orderBy('courses.beginning', 'desc')->get();

        return $bills;
    }

    public static function getBill($id){
        $bill = Bill::select('billings.*',
            DB::raw("CONCAT(training_actions.formative_action,' / ', courses.group, ' ', training_actions.name) as course"),
            'training_actions.formative_action as training_action',
            'courses.group as group',
            'companies.name as company',
            'payments.name as payment',
            'advisors.name as advisor',
            'course_statuses.name as status',
            'courses.beginning as beginning',
            DB::raw("YEAR(courses.beginning) as year"),
            DB::raw("CONCAT(users.name,' ',users.surname) as collaborator"),
            DB::raw("(CASE WHEN billings.is_bonus='1' THEN 'Bonificada' ELSE 'No bonificada' END) as type"),
            DB::raw("(CASE WHEN billings.invoiced='1' THEN 'Si' ELSE 'No' END) as invoice"),
            DB::raw("(CASE WHEN billings.charged='1' THEN 'Si' ELSE 'No' END) as charge"))
            ->leftjoin('courses', 'courses.id', '=', 'billings.course_id')
            ->leftjoin('companies', 'companies.id', '=', 'billings.company_id')
            ->leftjoin('payments', 'payments.id', '=', 'billings.payment_id')
            ->leftjoin('students', 'students.id', '=', 'billings.student_id')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'courses.training_action_id')
            ->leftjoin('advisors', 'advisors.id', '=', 'billings.advisor_id')
            ->leftjoin('users', 'users.id', '=', 'billings.collaborator_id')
            ->leftjoin('course_statuses', 'course_statuses.id', '=', 'courses.course_status_id')
            ->where('billings.id', $id)
            ->first();

        return $bill;
    }

    public static function updateBilling($id, $data){
        $bill = Bill::find($id);
        $total_training_activity = 0;

        if (isset($data['communication_start_date'])) {
            if ($bill->communication_start_date != $data['communication_start_date']){
                $status = 0;
                if ($data['communication_start_date']){
                    $status = 1;
                }
                Bill::updateStartCommunicationDate($id, $data['communication_start_date'], $status);
            }
        }
        if (isset($data['communication_end_date'])) {
            if ($bill->communication_end_date != $data['communication_end_date']){
                $status = 0;
                if ($data['communication_end_date']){
                    $status = 1;
                }
                Bill::updateCloseCommunicationDate($id, $data['communication_end_date'], $status);
            }
        }
        if (isset($data['bonus'])) {
            $total_training_activity = Bill::totalTrainingActivity($data['bonus']);
        }

        $bill->update([
            'number_students' => $data['number_students'],
            'billing' => $data['billing'] ? GeneralHelpers::convertComa($data['billing']) : 0,
            'bonus' => $data['bonus'] ? GeneralHelpers::convertComa($data['bonus']) : 0,
            'total_training_activity' => GeneralHelpers::convertComa($total_training_activity),
            'expenses' => $data['expenses'] ? GeneralHelpers::convertComa($data['expenses']) : 0,
            'salary_costs' => $data['salary_costs'] ? GeneralHelpers::convertComa($data['salary_costs']) : 0,
            'payment_id' => $data['payment_id'] ? $data['payment_id'] : null,
            'communication_start_date' => $data['communication_start_date'] ? Carbon::createFromFormat('d-m-Y', $data['communication_start_date'])->format('Y-m-d') : null,
            'communication_end_date' => $data['communication_end_date'] ? Carbon::createFromFormat('d-m-Y', $data['communication_end_date'])->format('Y-m-d') : null,
            'billing_number' => $data['billing_number'],
            'billing_date' => $data['billing_date'] ? Carbon::createFromFormat('d-m-Y', $data['billing_date'])->format('Y-m-d') : null,
            'collection_date' => $data['collection_date'] ? Carbon::createFromFormat('d-m-Y', $data['collection_date'])->format('Y-m-d') : null,
            'bonus_status' => $data['bonus_status'],
            'observation' => $data['observation'],
            'is_bonus' => $data['is_bonus'],
            'advisor_id' => $data['advisor_id'],
            'collaborator_id' => $data['collaborator_id'],
            'only_organizing_entity' => $data['only_organizing_entity'],
            'invoiced' => $data['invoiced'],
            'company_bonus' => $data['company_bonus'],
            'charged' => $data['charged'],
            'remitted' => $data['remitted']
        ]);
        return $bill;
    }

    public function updateBillingRegistrations($data){
        $bill = Bill::where('course_id', $data['course_id'])
            ->where('company_id', $data['company_id'])
            ->where('is_bonus', $data['is_bonus'])->first();
        $company = Company::find($data['company_id']);

        if ($bill && $company['name'] != 'SIN EMPRESA'){
            $total_training_activity = GeneralHelpers::convertComa($data['price']) + $bill['billing'];
            $expenses = Bill::calculateExpenses($data['price'] + $bill['billing'], $total_training_activity);
            $bill->update([
                'number_students' => $bill['number_students']+1,
                'billing' => $data['price'] + $bill['billing'],
                'total_training_activity' => $total_training_activity,
                'expenses' => $expenses,
                'advisor_id' => $data['advisor_id'],
                'collaborator_id' => $data['collaborator_id']
            ]);
        } else {
            $total_training_activity = GeneralHelpers::convertComa($data['price']);
            $expenses = Bill::calculateExpenses($data['price'], $total_training_activity);
            $bill = Bill::create([
                'course_id' => $data['course_id'],
                'company_id' => $data['company_id'],
                'number_students' => 1,
                'is_bonus' => $data['is_bonus'],
                'billing' => GeneralHelpers::convertComa($data['price']),
                'total_training_activity' => $total_training_activity,
                'expenses' => $expenses,
                'advisor_id' => $data['advisor_id'],
                'collaborator_id' => $data['collaborator_id']
            ]);
            if ($company['name'] == 'SIN EMPRESA'){
                $bill->update([
                    'student_id' => $data['student_id']
                ]);
            }
        }
        return $bill;
    }

    public static function calculateExpenses($price, $total){
        return GeneralHelpers::convertComa($price - $total);
    }

    public static function totalTrainingActivity($bonus){
        return round($bonus / 1.1, 2);
    }

    public static function updateStartCommunicationDate($id, $date, $status){
        $bill = Bill::find($id);
        $bill->update([
            'communication_start_date' => $date ? Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d') : null,
        ]);
        $registrations = Registration::billingRegistration($id);
        foreach ($registrations as $registration) {
            Chore::updateCommunicationStartDate($registration->chore_id, $date, $status);
        }
    }
    public static function updateCloseCommunicationDate($id, $date, $status){
        $bill = Bill::find($id);
        $bill->update([
            'communication_end_date' => $date ? Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d') : null
        ]);
        $registrations = Registration::billingRegistration($id);
        foreach ($registrations as $registration) {
            Chore::updateCommunicationEndDate($registration->chore_id, $date, $status);
        }
    }

    public static function updateInvicedDate($id, $date, $status){
        $bill = Bill::find($id);
        $bill->update([
            'billing_date' => $date ? Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d') : null,
            'invoiced' => $status,
            'bonus_status' => $status
        ]);
        $registrations = Registration::billingRegistration($id);
        foreach ($registrations as $registration) {
            Chore::billingDateChore($registration->chore_id, $date, $status);
        }
    }

    public static function getBillCSV($course = null, $company = null, $type = null, $invoiced = null, $charged = null){
        $bills = Bill::select('billings.*',
            DB::raw("CONCAT(training_actions.formative_action,' / ', courses.group, ' ', training_actions.name) as course"),
            'training_actions.formative_action as training_action',
            'courses.group as group',
            'companies.name as company',
            'payments.name as payment',
            'advisors.name as advisor',
            'course_statuses.name as status',
            'courses.beginning as beginning',
            DB::raw("YEAR(courses.beginning) as year"),
            DB::raw("CONCAT(users.name,' ',users.surname) as collaborator"),
            DB::raw("(CASE WHEN billings.is_bonus='1' THEN 'Bonificada' ELSE 'No bonificada' END) as type"),
            DB::raw("(CASE WHEN billings.invoiced='1' THEN 'Si' ELSE 'No' END) as invoice"),
            DB::raw("(CASE WHEN billings.charged='1' THEN 'Si' ELSE 'No' END) as charge"))
            ->leftjoin('courses', 'courses.id', '=', 'billings.course_id')
            ->leftjoin('companies', 'companies.id', '=', 'billings.company_id')
            ->leftjoin('payments', 'payments.id', '=', 'billings.payment_id')
            ->leftjoin('students', 'students.id', '=', 'billings.student_id')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'courses.training_action_id')
            ->leftjoin('advisors', 'advisors.id', '=', 'billings.advisor_id')
            ->leftjoin('users', 'users.id', '=', 'billings.collaborator_id')
            ->leftjoin('course_statuses', 'course_statuses.id', '=', 'courses.course_status_id');

        if ($course) {
            $bills = $bills->where('training_actions.name', 'like', '%'.$course.'%');
        }
        if ($company) {
            $bills = $bills->where('companies.name', 'like', '%'.$company.'&');
        }
        if ($type) {
            if ($type === 'No bonificada') {
                $bills = $bills->where('billings.is_bonus', 1);
            } else if ($type === 'Bonificada') {
                $bills = $bills->where('billings.is_bonus', 0);
            }
        }
        if ($invoiced) {
            if ($invoiced === 'Si') {
                $bills = $bills->where('billings.invoiced', 1);
            } else if ($invoiced === 'No') {
                $bills = $bills->where('billings.invoiced', 0);
            }
        }
        if ($charged) {
            if ($charged === 'Si') {
                $bills = $bills->where('billings.charge', 1);
            } else if ($charged === 'No') {
                $bills = $bills->where('billings.charge', 0);
            }
        }
        $cfa = CourseType::where('name', 'CFA')->first();
        if ($cfa) {
            $bills = $bills->where('course_type_id', '!=', $cfa->id);
        }

        $bills = $bills->orderBy('courses.beginning', 'desc')->get();

        $data = [];
        foreach ($bills as $bill) {
            $element = [
                'Nº Factura' => $bill['billing_number'],
                'Curso' => $bill['course'],
                'Año' => $bill['year'],
                'Tipo' => $bill['type'],
                'Empresa' => $bill['company'],
                'Asesoría' => $bill['advisor'],
                'Collaborador' => $bill['collaborator'],
                'Numero Alumnos' => $bill['number_students'],
                'Factura' => $bill['billing'],
                'Fecha Factura' => $bill['billing_date'],
                'Fecha Cobro' => $bill['collection_date'],
                'Cobrado' => $bill['charge']
            ];
            $data[] = $element;
        }
        return $data;
    }
}
