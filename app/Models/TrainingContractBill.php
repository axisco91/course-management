<?php

namespace App\Models;

use App\Helpers\GeneralHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TrainingContractBill extends Model
{
	use HasFactory;

    public $timestamps = true;

    protected $fillable = ['number',
        'training_contract_id',
        'training_contract_bonus_id',
        'company_id',
        'series',
        'collection_date',
        'month',
        'year',
        'amount',
        'modalities',
        'hours',
        'price_hours',
        'charged',
        'invoiced'
    ];

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

    public static function getTrainingContractBills(){
        $bills = TrainingContractBill::select('training_contract_bills.*',
            'companies.name as company',
            'companies.id as company_id',
            'students.id as student_id',
            'training_contracts.number_cfa',
            DB::raw("CONCAT(students.name,' ', students.surname) as student"),
            DB::raw("(CASE WHEN training_contract_bills.invoiced='1' THEN 'Si' ELSE 'No' END) as invoice"),
            DB::raw("(CASE WHEN training_contract_bills.charged='1' THEN 'Si' ELSE 'No' END) as charge"))
            ->leftjoin('companies', 'companies.id', '=', 'training_contract_bills.company_id')
            ->leftjoin('training_contracts', 'training_contracts.id', '=', 'training_contract_bills.training_contract_id')
            ->leftjoin('students', 'students.id', '=', 'training_contracts.student_id')
            ->orderBy('training_contract_bills.number', 'desc')->get();

        foreach ($bills as $bill) {
            switch ($bill['month']) {
                case 1:
                    $bill['month_name'] = 'Enero';
                    break;
                case 2:
                    $bill['month_name'] = 'Febrero';
                    break;
                case 3:
                    $bill['month_name'] = 'Marzo';
                    break;
                case 4:
                    $bill['month_name'] = 'Abril';
                    break;
                case 5:
                    $bill['month_name'] = 'Mayo';
                    break;
                case 6:
                    $bill['month_name'] = 'Junio';
                    break;
                case 7:
                    $bill['month_name'] = 'Julio';
                    break;
                case 8:
                    $bill['month_name'] = 'Agosto';
                    break;
                case 9:
                    $bill['month_name'] = 'Septiembre';
                    break;
                case 10:
                    $bill['month_name'] = 'Octubre';
                    break;
                case 11:
                    $bill['month_name'] = 'Noviembre';
                    break;
                case 12:
                    $bill['month_name'] = 'Diciembre';
                    break;
            }
        }

        return $bills;
    }

    public static function getTrainingContractBill($id){
        $bill = TrainingContractBill::select('training_contract_bills.*',
            'companies.name as company',
            'companies.id as company_id',
            'students.id as student_id',
            'training_contracts.number_cfa',
            DB::raw("CONCAT(students.name,' ', students.surname) as student"),
            DB::raw("(CASE WHEN training_contract_bills.invoiced='1' THEN 'Si' ELSE 'No' END) as invoice"),
            DB::raw("(CASE WHEN training_contract_bills.charged='1' THEN 'Si' ELSE 'No' END) as charge"))
            ->leftjoin('companies', 'companies.id', '=', 'training_contract_bills.company_id')
            ->leftjoin('training_contracts', 'training_contracts.id', '=', 'training_contract_bills.training_contract_id')
            ->leftjoin('students', 'students.id', '=', 'training_contracts.student_id')
            ->where('training_contract_bills.id', $id)->first();

        switch ($bill['month']) {
            case 1:
                $bill['month_name'] = 'Enero';
                break;
            case 2:
                $bill['month_name'] = 'Febrero';
                break;
            case 3:
                $bill['month_name'] = 'Marzo';
                break;
            case 4:
                $bill['month_name'] = 'Abril';
                break;
            case 5:
                $bill['month_name'] = 'Mayo';
                break;
            case 6:
                $bill['month_name'] = 'Junio';
                break;
            case 7:
                $bill['month_name'] = 'Julio';
                break;
            case 8:
                $bill['month_name'] = 'Agosto';
                break;
            case 9:
                $bill['month_name'] = 'Septiembre';
                break;
            case 10:
                $bill['month_name'] = 'Octubre';
                break;
            case 11:
                $bill['month_name'] = 'Noviembre';
                break;
            case 12:
                $bill['month_name'] = 'Diciembre';
                break;
        }

        return $bill;
    }

    public static function createBill($bonus) {
        $id = TrainingContractBill::orderBy('id', 'desc')->first();
        $training_contract = TrainingContract::where('id', $bonus['training_contract_id'])->first();
        if ($id){
            $number = $id['id'] + 1;
        } else {
            $number = 1;
        }
        $bill = TrainingContractBill::create([
            'number' => $number,
            'training_contract_bonus_id' => $bonus->id,
            'training_contract_id' => $training_contract['id'],
            'company_id' => $training_contract['company_id'],
            'series' => 3,
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
        $bill = $bill->update([
            $data['collection_date'] ? Carbon::createFromFormat('d-m-Y', $data['collection_date'])->format('Y-m-d') : null,
            'charged' => $data['charged'],
            'invoiced' => $data['invoiced']
        ]);

        return $bill;
    }

    public static function getBillCSV($student = null, $company = null, $month = null, $year = null, $invoiced = null, $charged = null){
        $bills = TrainingContractBill::select('training_contract_bills.*',
            'companies.name as company',
            'training_contracts.number_cfa',
            DB::raw("CONCAT(students.name,' ', students.surname) as student"),
            DB::raw("(CASE WHEN training_contract_bills.invoiced='1' THEN 'Si' ELSE 'No' END) as invoice"),
            DB::raw("(CASE WHEN training_contract_bills.charged='1' THEN 'Si' ELSE 'No' END) as charge"))
            ->leftjoin('companies', 'companies.id', '=', 'training_contract_bills.company_id')
            ->leftjoin('training_contracts', 'training_contracts.id', '=', 'training_contract_bills.training_contract_id')
            ->leftjoin('students', 'students.id', '=', 'training_contracts.student_id');

        if ($student) {
            $bills = $bills->where('students.name', 'like', '%'.$student.'%');
        }
        if ($company) {
            $bills = $bills->where('companies.name', 'like', '%'.$company.'&');
        }
        if ($month) {
            switch ($month) {
                case 'Enero':
                    $bill['month'] = 1;
                    break;
                case 'Febrero':
                    $bill['month'] = 2;
                    break;
                case 'Marzo':
                    $bill['month'] = 3;
                    break;
                case 'Abril':
                    $bill['month'] = 4;
                    break;
                case 'Mayo':
                    $bill['month'] = 5;
                    break;
                case 'Junio':
                    $bill['month'] = 6;
                    break;
                case 'Julio':
                    $bill['month'] = 7;
                    break;
                case 'Agosto':
                    $bill['month'] = 8;
                    break;
                case 'Septiembre':
                    $bill['month'] = 9;
                    break;
                case 'Octubre':
                    $bill['month'] = 10;
                    break;
                case 'Noviembre':
                    $bill['month'] = 11;
                    break;
                case 'Diciembre':
                    $bill['month'] = 12;
                    break;
            }
        }
        if ($year) {
                $bills = $bills->where('training_contract_bills.year', $year);
        }
        if ($invoiced) {
            if ($invoiced === 'Si') {
                $bills = $bills->where('training_contract_bills.invoiced', 1);
            } else if ($invoiced === 'No') {
                $bills = $bills->where('training_contract_bills.invoiced', 0);
            }
        }
        if ($charged) {
            if ($charged === 'Si') {
                $bills = $bills->where('training_contract_bills.charge', 1);
            } else if ($charged === 'No') {
                $bills = $bills->where('training_contract_bills.charge', 0);
            }
        }

        $bills = $bills->orderBy('training_contract_bills.number', 'desc')->get();

        $data = [];
        foreach ($bills as $bill) {
            switch ($bill['month']) {
                case 1:
                    $bill['month_name'] = 'Enero';
                    break;
                case 2:
                    $bill['month_name'] = 'Febrero';
                    break;
                case 3:
                    $bill['month_name'] = 'Marzo';
                    break;
                case 4:
                    $bill['month_name'] = 'Abril';
                    break;
                case 5:
                    $bill['month_name'] = 'Mayo';
                    break;
                case 6:
                    $bill['month_name'] = 'Junio';
                    break;
                case 7:
                    $bill['month_name'] = 'Julio';
                    break;
                case 8:
                    $bill['month_name'] = 'Agosto';
                    break;
                case 9:
                    $bill['month_name'] = 'Septiembre';
                    break;
                case 10:
                    $bill['month_name'] = 'Octubre';
                    break;
                case 11:
                    $bill['month_name'] = 'Noviembre';
                    break;
                case 12:
                    $bill['month_name'] = 'Diciembre';
                    break;
            }
            $element = [
                'Nº Factura' => $bill['number'],
                'Nº CFA' => $bill['number_cfa'],
                'Alumno' => $bill['student'],
                'Empresa' => $bill['company'],
                'Mes' => $bill['month_name'],
                'Año' => $bill['year'],
                'Facturado' => $bill['invoice'],
                'Fecha Cobro' => $bill['collection_date'],
                'Cobrado' => $bill['charge']
            ];
            $data[] = $element;
        }
        return $data;
    }
}
