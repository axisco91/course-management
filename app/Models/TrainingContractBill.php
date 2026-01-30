<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TrainingContractBill extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'number',
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
        'main_company_id'
    ];

    public function company()
    {
        return $this->hasOne('App\Models\Company', 'id', 'company_id');
    }

    public function provider()
    {
        return $this->hasOne('App\Models\Provider', 'id', 'company_id');
    }

    public function training_contract()
    {
        return $this->hasOne('App\Models\TrainingContract', 'id', 'training_contract_id');
    }

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

    public function scopeGetTrainingContractBill($query, $mainCompanyId)
    {
        return $query->select(
            'training_contract_bills.*',
            'companies.name as company',
            'companies.id as company_id',
            'students.id as student_id',
            'training_contracts.number_cfa',
            DB::raw("CONCAT(students.name,' ', students.surname) as student"),
            DB::raw("(CASE WHEN training_contract_bills.invoiced = '1' THEN 'Si' ELSE 'No' END) as invoice"),
            DB::raw("(CASE WHEN training_contract_bills.charged = '1' THEN 'Si' ELSE 'No' END) as charge"),
            DB::raw("(CASE
                        WHEN training_contract_bills.month = 1  THEN 'Enero'
                        WHEN training_contract_bills.month = 2  THEN 'Febrero'
                        WHEN training_contract_bills.month = 3  THEN 'Marzo'
                        WHEN training_contract_bills.month = 4  THEN 'Abril'
                        WHEN training_contract_bills.month = 5  THEN 'Mayo'
                        WHEN training_contract_bills.month = 6  THEN 'Junio'
                        WHEN training_contract_bills.month = 7  THEN 'Julio'
                        WHEN training_contract_bills.month = 8  THEN 'Agosto'
                        WHEN training_contract_bills.month = 9  THEN 'Septiembre'
                        WHEN training_contract_bills.month = 10 THEN 'Octubre'
                        WHEN training_contract_bills.month = 11 THEN 'Noviembre'
                        WHEN training_contract_bills.month = 12 THEN 'Diciembre'
                        ELSE NULL
                      END) as month_name")
        )
            ->where('training_actions.main_company_id', $mainCompanyId)
            ->leftJoin('companies', 'companies.id', '=', 'training_contract_bills.company_id')
            ->leftJoin('training_contracts', 'training_contracts.id', '=', 'training_contract_bills.training_contract_id')
            ->leftJoin('students', 'students.id', '=', 'training_contracts.student_id');
    }

    public static function createBill($bonus)
    {
        $trainingContract = TrainingContract::where('id', $bonus['training_contract_id'])->first();

        $bill = TrainingContractBill::create([
            'number' => null,  // No se genera el número aquí
            'training_contract_bonus_id' => $bonus->id,
            'training_contract_id' => $trainingContract['id'],
            'company_id' => $trainingContract['company_id'],
            'series_id' => $trainingContract['series_id'],
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

    public static function updateBill($id, $data)
    {
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
