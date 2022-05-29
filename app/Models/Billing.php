<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
	use HasFactory;

    public $timestamps = true;

    protected $table = 'billings';

    protected $fillable = ['course_id','company_id','number_students','billing','bonus','total_training_activity','expenses','only_organizing_entity','salary_costs','payment_id','communication_start_date','comunication_end_date','invoiced','billing_number','billing_date','collection_date','bonus_status','company_bonus','observation', 'is_bonus'];

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

    public function getBillings($keyWord){
        $billings = Billing::latest()
            ->select('billings.*', 'training_actions.name as course', 'training_actions.formative_action as training_action', 'courses.group as group', 'companies.name as company', 'payments.name as payment')
            ->leftjoin('courses', 'courses.id', '=', 'billings.course_id')
            ->leftjoin('companies', 'companies.id', '=', 'billings.company_id')
            ->leftjoin('payments', 'payments.id', '=', 'billings.payment_id')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'courses.training_action_id')
            ->orWhere('courses.name', 'LIKE', $keyWord)
            ->orWhere('companies.name', 'LIKE', $keyWord)
            ->orWhere('number_students', 'LIKE', $keyWord)
            ->orWhere('billing', 'LIKE', $keyWord)
            ->orWhere('bonus', 'LIKE', $keyWord)
            ->orWhere('total_training_activity', 'LIKE', $keyWord)
            ->orWhere('expenses', 'LIKE', $keyWord)
            ->orWhere('only_organizing_entity', 'LIKE', $keyWord)
            ->orWhere('salary_costs', 'LIKE', $keyWord)
            ->orWhere('payments.name', 'LIKE', $keyWord)
            ->orWhere('communication_start_date', 'LIKE', $keyWord)
            ->orWhere('comunication_end_date', 'LIKE', $keyWord)
            ->orWhere('invoiced', 'LIKE', $keyWord)
            ->orWhere('billing_number', 'LIKE', $keyWord)
            ->orWhere('billing_date', 'LIKE', $keyWord)
            ->orWhere('collection_date', 'LIKE', $keyWord)
            ->orWhere('bonus_status', 'LIKE', $keyWord)
            ->orWhere('company_bonus', 'LIKE', $keyWord)
            ->orWhere('observation', 'LIKE', $keyWord)
            ->paginate(10);

        return $billings;
    }

    public function updateBilling($id, $data){
        $billing = Billing::find($id);
        $billing->update([
            'course_id' => $data['course_id'],
            'company_id' => $data['company_id'],
            'number_students' => $data['number_students'],
            'billing' => $data['billing'],
            'bonus' => $data['bonus'],
            'total_training_activity' => $data['total_training_activity'],
            'expenses' => $data['expenses'],
            'only_organizing_entity' => $data['only_organizing_entity'],
            'salary_costs' => $data['salary_costs'],
            'payment_id' => $data['payment_id'],
            'communication_start_date' => $data['communication_start_date'],
            'comunication_end_date' => $data['comunication_end_date'],
            'invoiced' => $data['invoiced'],
            'billing_number' => $data['billing_number'],
            'billing_date' => $data['billing_date'],
            'collection_date' => $data['collection_date'],
            'bonus_status' => $data['bonus_status'],
            'company_bonus' => $data['company_bonus'],
            'observation' => $data['observation'],
            'is_bonus' => $data['is_bonus'],
        ]);

        return $billing;
    }

    public function updateBillingRegistrations($data){
        $billing = Billing::where('course_id', $data['course_id'])
            ->where('company_id', $data['company_id'])
            ->where('is_bonus', $data['is_bonus'])->first();
        if ($billing){
            $expenses = $this->calculateExpenses($data['price'] + $billing['billing']);
            $billing->update([
                'number_students' => $billing['number_students']+1,
                'billing' => $data['price'] + $billing['billing'],
                'total_training_activity' => $data['price'] + $billing['billing'],
                'expenses' => $expenses
            ]);
        } else {
            $expenses = $this->calculateExpenses($this->price);
            Billing::create([
                'course_id' => $data['course_id'],
                'company_id' => $data['company_id'],
                'number_students' => 1,
                'is_bonus' => $data['is_bonus'],
                'billing' => $data['price'],
                'total_training_activity' => $data['price'],
                'expenses' => $expenses
            ]);
        }
    }

    private function calculateExpenses($precio){
        return (10/100) * $precio;
    }
}
