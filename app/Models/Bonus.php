<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
	use HasFactory;

    public $timestamps = true;

    protected $fillable = ['course_id','company_id','course_status_id','number_students','billing','bonus','total_training_activity','organization_expenses','only_organizing_entity','average_template','salary_cost','payment_id','start_communication_date','close_communication_date','invoiced','invoice_number','invoice_date','collection_date','status_bonus','date','company_bonus','observations'];

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
    public function courseStatus()
    {
        return $this->hasOne('App\Models\CourseStatus', 'id', 'course_status_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function payment()
    {
        return $this->hasOne('App\Models\Payment', 'id', 'payment_id');
    }

    public static function getBonuses($keyWord){
        $bonuses = Bonus::latest()
            ->orWhere('course_id', 'LIKE', $keyWord)
            ->orWhere('company_id', 'LIKE', $keyWord)
            ->orWhere('course_status_id', 'LIKE', $keyWord)
            ->orWhere('number_students', 'LIKE', $keyWord)
            ->orWhere('billing', 'LIKE', $keyWord)
            ->orWhere('bonus', 'LIKE', $keyWord)
            ->orWhere('total_training_activity', 'LIKE', $keyWord)
            ->orWhere('organization_expenses', 'LIKE', $keyWord)
            ->orWhere('only_organizing_entity', 'LIKE', $keyWord)
            ->orWhere('average_template', 'LIKE', $keyWord)
            ->orWhere('salary_cost', 'LIKE', $keyWord)
            ->orWhere('payment_id', 'LIKE', $keyWord)
            ->orWhere('start_communication_date', 'LIKE', $keyWord)
            ->orWhere('close_communication_date', 'LIKE', $keyWord)
            ->orWhere('invoiced', 'LIKE', $keyWord)
            ->orWhere('invoice_number', 'LIKE', $keyWord)
            ->orWhere('invoice_date', 'LIKE', $keyWord)
            ->orWhere('collection_date', 'LIKE', $keyWord)
            ->orWhere('status_bonus', 'LIKE', $keyWord)
            ->orWhere('date', 'LIKE', $keyWord)
            ->orWhere('company_bonus', 'LIKE', $keyWord)
            ->orWhere('observations', 'LIKE', $keyWord)
            ->paginate(10);

        return $bonuses;
    }

    public function createBonus(){

    }

    public function updateBonus(){

    }

}
