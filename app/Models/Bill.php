<?php

namespace App\Models;

use App\Helpers\GeneralHelpers;
use App\Services\BillService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Bill extends Model
{
	use HasFactory;

    public $timestamps = true;

    protected $table = 'billings';

    protected $fillable = ['course_id','company_id','number_students','billing','bonus','total_training_activity','expenses','only_organizing_entity','salary_costs','payment_id','communication_start_date','communication_end_date','invoiced','billing_number','billing_date','collection_date','bonus_status','company_bonus','observation', 'is_bonus', 'student_id', 'advisor_id', 'charged', 'collaborator_id', 'remitted', 'main_company_id'];

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

    public function scopeBill($query, $mainCompanyId) {
        return $query->select(
            'billings.*',
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
            DB::raw("(CASE WHEN billings.charged='1' THEN 'Si' ELSE 'No' END) as charge"),
            DB::raw("(CASE WHEN billings.bonus_status='1' THEN 'Enviado' ELSE 'Pendiente' END) as bonus_status_name"),
            'advisor_commission_types.percentage' // ✅ Select the percentage field
        )
            ->leftJoin('courses', 'courses.id', '=', 'billings.course_id')
            ->leftJoin('companies', 'companies.id', '=', 'billings.company_id')
            ->leftJoin('payments', 'payments.id', '=', 'billings.payment_id')
            ->leftJoin('students', 'students.id', '=', 'billings.student_id')
            ->leftJoin('training_actions', 'training_actions.id', '=', 'courses.training_action_id')
            ->leftJoin('advisors', 'advisors.id', '=', 'billings.advisor_id')
            ->leftJoin('users', 'users.id', '=', 'billings.collaborator_id')
            ->leftJoin('course_statuses', 'course_statuses.id', '=', 'courses.course_status_id')
            ->leftJoin('advisor_commission_types', function($join) {
                $join->on('advisor_commission_types.advisor_id', '=', 'advisors.id')
                    ->orderBy('advisor_commission_types.id', 'desc'); // ✅ Gets the latest commission
            })
        ->where('billings.main_company_id', $mainCompanyId);
    }

    public function scopeFilterMainCompany($query, $mainCompanyId) {
        return $query->where('billings.main_company_id', $mainCompanyId);
    }

    /*
    public static function calculateExpenses($price, $total){
        return GeneralHelpers::convertComa($price - $total);
    }
    */

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

    public static function createWithService($data)
    {
        $service = app(BillService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(BillService::class);
        return $service->update($this, $data);
    }

    public static function createBillingRegistrations($data)
    {
        $service = app(BillService::class);
        return $service->createBillingRegistrations($data);
    }

    public function updateBillingRegistrations($data){
        $service = app(BillService::class);
        return $service->updateBillingRegistrations($this, $data);
    }
}
