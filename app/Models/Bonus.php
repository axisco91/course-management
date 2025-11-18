<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
	use HasFactory;

    public $timestamps = true;

    protected $fillable = ['course_id','company_id','course_status_id','number_students','billing','bonus','total_training_activity','organization_expenses','only_organizing_entity','average_template','salary_cost','payment_id','start_communication_date','close_communication_date','invoiced','invoice_number','invoice_date','collection_date','status_bonus','date','company_bonus','observations', 'main_company_id'];

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
}
