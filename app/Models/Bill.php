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

    public function company() { return $this->belongsTo(Company::class); }
    public function course() { return $this->belongsTo(Course::class); }
    public function payment() { return $this->belongsTo(Payment::class); }
    public function advisor() { return $this->belongsTo(Advisor::class); }
    public function collaborator() { return $this->belongsTo(User::class, 'collaborator_id'); }


    public function scopeBill($query, $mainCompanyId)
    {
        return $query
            ->select('billings.*')
            ->with([
                'course:id,training_action_id,course_status_id,beginning,group,course_type_id',
                'course.trainingAction:id,formative_action,name,total_hours',
                'course.courseStatus:id,name',
                'company:id,name',
                'payment:id,name',
                'advisor:id,name',
                'collaborator:id,name,surname',
            ])
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
