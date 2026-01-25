<?php

namespace App\Models;

use App\Services\PaymentService;
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

    public function scopeGetPayment($query)
    {
        return $query
            ->select(
                'payments.*',
                'payments.id as value',
                'payments.name as label'
            )
            ->leftJoin('billings', 'billings.payment_id', '=', 'payments.id')
            ->selectRaw('CASE WHEN billings.id IS NULL THEN false ELSE true END as used')
            ->groupBy('payments.id');
    }

    public static function createWithService($data)
    {
        $service = app(PaymentService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(PaymentService::class);
        return $service->update($this, $data);
    }
}
