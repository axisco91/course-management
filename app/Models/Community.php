<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Community extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    public function scopeCommunitiesWithFestivals($query, $start = null, $end = null){
        $query->select('communities.*', 'communities.id as value', 'communities.name as label')
            ->leftjoin('community_festivals', 'community_festivals.community_id', '=', 'communities.id');
        if ($start && $end){
            $query->whereBetween('community_festivals.day', [$start, $end]);
        }
        $query->groupBy('communities.id');
        return $query;
    }
}
