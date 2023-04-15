<?php

namespace App\Models;

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

    public static function getQuoteGroups(){
        $quote_groups = QuoteGroup::
        select('quote_groups.*', 'id as value', 'name as label')
            ->get();
        return $quote_groups;
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
}
