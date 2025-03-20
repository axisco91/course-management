<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Liquidation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['company_id', 'course_id', 'beginning', 'end', 'price', 'paid', 'commision_percent', 'commision', 'paid_date', 'invoice_date', 'advisor_id', 'bill_number', 'status'];

    public function course()
    {
        return $this->hasOne('App\Models\Course', 'id', 'course_id');
    }

    public function company()
    {
        return $this->hasOne('App\Models\Company', 'id', 'company_id');
    }

    public function advisor()
    {
        return $this->hasOne('App\Models\Advisor', 'id', 'advisor_id');
    }

    public static function getLiquidations(){
        $liquidations = Liquidation::select('*')
            ->with('course')
            ->with('company')
            ->with('advisor')
            ->get();

        return $liquidations;
    }

    public static function getLiquidation($id)
    {
        $liquidation = Liquidation::with([
            'course' => function ($query) {
                $query->withCourseData(); // Apply the scope to the course relationship
            },
            'company',
            'advisor'
        ])->where('id', $id)->first();

        return $liquidation;
    }


    public static function createLiquidation($data){
        return Liquidation::create([
            'company_id' => $data['company_id'],
            'course_id' => $data['course_id'],
            'beginning' => $data['beginning'],
            'end' => $data['end'],
            'price' => $data['price'],
            'paid' => $data['paid'],
            'commision_percent' => $data['commision_percent'],
            'commision' => $data['commision'],
            'paid_date' => $data['paid_date'] ?? Carbon::parse($data['paid_date'])->format('Y-m-d'),
            'invoice_date' => $data['invoice_date'] ?? Carbon::parse($data['invoice_date'])->format('Y-m-d'),
            'advisor_id' => $data['advisor_id'],
            'bill_number' => $data['bill_number'],
            'status' => $data['status'] ?? 1,
        ]);
    }

    public static function updateLiquidation($id, $data){
        $liquidation = Liquidation::find($id);
         $liquidation->update([
            'company_id' => $data['company_id'],
            'course_id' => $data['course_id'],
            'beginning' => $data['beginning'],
            'end' => $data['end'],
            'price' => $data['price'],
            'paid' => $data['paid'],
            'commision_percent' => $data['commision_percent'],
            'commision' => $data['commision'],
            'paid_date' => $data['paid_date'] ? Carbon::parse($data['paid_date'])->format('Y-m-d') : '',
            'invoice_date' => $data['invoice_date'] ? Carbon::parse($data['invoice_date'])->format('Y-m-d') : '',
            'advisor_id' => $data['advisor_id'],
            'bill_number' => $data['bill_number'],
            'status' => $data['status'] ?? 1,
        ]);
         return $liquidation;
    }
}
