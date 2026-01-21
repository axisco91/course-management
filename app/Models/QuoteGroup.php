<?php

namespace App\Models;

use App\Services\QuoteGroupService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteGroup extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function students()
    {
        return $this->hasMany('App\Models\Student', 'quote_group_id', 'id');
    }

    public function scopeGetQuoteGroups($query){
        return $query->select('quote_groups.*', 'id as value', 'name as label');
    }

    public static function createQuoteGroup($data){
        $quote_group = QuoteGroup::create([
            'name' => $data['name']
        ]);
        return $quote_group;
    }

    public static function updateQuoteGroup($id, $data){
        $quote_group = QuoteGroup::find($id);
        $quote_group->update([
            'name' => $data['name']
        ]);
        return $quote_group;
    }

    public static function createWithService($data)
    {
        $service = app(QuoteGroupService::class);
        return $service->create($data);
    }

    public function updateWithService($data){
        $service = app(QuoteGroupService::class);
        return $service->update($this, $data);
    }
}
