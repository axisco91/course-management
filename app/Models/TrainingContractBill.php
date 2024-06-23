<?php

namespace App\Models;

use App\Helpers\GeneralHelpers;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TrainingContractBill extends Model
{
	use HasFactory;

    public $timestamps = true;

    protected $fillable = ['number',
        'training_contract_id',
        'training_contract_bonus_id',
        'company_id',
        'collection_date',
        'month',
        'year',
        'amount',
        'hours',
        'price_hours',
        'charged',
        'invoiced',
        'series_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function company()
    {
        return $this->hasOne('App\Models\Company', 'id', 'company_id');
    }
    public function provider()
    {
        return $this->hasOne('App\Models\Provider', 'id', 'company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function training_contract()
    {
        return $this->hasOne('App\Models\TrainingContract', 'id', 'training_contract_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function training_contract_bonus()
    {
        return $this->hasOne('App\Models\TrainingContractBonus', 'id', 'training_contract_bonus_id');
    }

    public function series()
    {
        return $this->belongsTo('App\Models\TrainingContractSeries', 'series_id', 'id');
    }
    public function student()
    {
        return $this->hasOneThrough(
            'App\Models\Student',
            'App\Models\TrainingContract',
            'id', // Clave foránea en la tabla intermedia (TrainingContract)
            'id', // Clave foránea en la tabla final (Student)
            'training_contract_id', // Clave local en la tabla inicial (TrainingContractBill)
            'student_id' // Clave local en la tabla intermedia (TrainingContract)
        );
    }
    public function scopeGetTrainingContractBills($query){
        return $query->select('training_contract_bills.*',
            'companies.name as company',
            'companies.id as company_id',
            'students.id as student_id',
            'training_contracts.number_cfa',
            DB::raw("CONCAT(students.name,' ', students.surname) as student"),
            DB::raw("(CASE WHEN training_contract_bills.invoiced='1' THEN 'Si' ELSE 'No' END) as invoice"),
            DB::raw("(CASE WHEN training_contract_bills.charged='1' THEN 'Si' ELSE 'No' END) as charge"))
            ->leftjoin('companies', 'companies.id', '=', 'training_contract_bills.company_id')
            ->leftjoin('training_contracts', 'training_contracts.id', '=', 'training_contract_bills.training_contract_id')
            ->leftjoin('students', 'students.id', '=', 'training_contracts.student_id');
    }

    public static function createBill($bonus) {
        $training_contract = TrainingContract::where('id', $bonus['training_contract_id'])->first();
    
        $bill = TrainingContractBill::create([
            'number' => null,  // No se genera el número aquí
            'training_contract_bonus_id' => $bonus->id,
            'training_contract_id' => $training_contract['id'],
            'company_id' => $training_contract['company_id'],
            'series_id' => $training_contract['series_id'],
            'modality' => 'TELEFORMACIÓN/PRESENCIAL',
            'collection_date' => null,
            'month' => $bonus->month,
            'year' => $bonus->year,
            'amount' => $bonus->amount,
            'hours' => $bonus->amount / 5,
            'price_hours' => 5,
            'charged' => 0,
            'invoiced' => 0
        ]);
    
        return $bill;
    }

   
    public static function updateBill($id, $data) {
       
        $bill = TrainingContractBill::find($id);
        $bill->update([
            'collection_date' => $data['collection_date'] ? Carbon::createFromFormat('d-m-Y', $data['collection_date'])->format('Y-m-d') : null,
            'charged' => $data['charged'],
            'invoiced' => $data['invoiced'],
            'series_id' => $data['series_id']
        ]);
    
        return $bill->fresh();
    }
}
