<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bonuses()
    {
        return $this->hasMany('App\Models\Bonus', 'payment_id', 'id');
    }

    public static function getPayments(){
        $payments = Payment::select('*', 'id as value', 'name as label')
            ->get();
        foreach ($payments as $payment){
            $bill = Bill::where('payment_id', $payment['id'])->first();
            if ($bill){
                $payment['used'] = true;
            } else{
                $payment['used'] = false;
            }
        }
        return $payments;
    }

    public static function getPayment($id){
        $payment = Payment::select('*', 'id as value', 'name as label')
            ->where('id', $id)
            ->first();
        $bill = Bill::where('payment_id', $payment['id'])->first();
        if ($bill){
            $payment['used'] = true;
        } else{
            $payment['used'] = false;
        }
        return $payment;
    }

    public static function createPayment($data){
        $payment = Payment::create([
            'name' => $data['name']
        ]);
        return $payment;
    }

    public static function updatePayment($id, $data){
        $payment = Payment::find($id);
        $payment->update([
            'name' => $data['name']
        ]);
        return $payment;
    }

}
