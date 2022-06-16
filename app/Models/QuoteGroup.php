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

    public function getQuoteGroups($keyWord){
        $quote_groups = QuoteGroup::
        orWhere('name', 'LIKE', $keyWord)
            ->paginate(10);
        return $quote_groups;
    }

    public function createQuoteGroup($data){
        $quote_group = QuoteGroup::create([
            'name' => $data['name']
        ]);
        return $quote_group;
    }

    public function updateQuoteGroup($id, $data){
        $quote_group = QuoteGroup::find($id);
        $quote_group->update([
            'name' => $data['name']
        ]);
        return $quote_group;
    }
}
