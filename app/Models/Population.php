<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Population extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    public function scopePopulationsWithFestivals($query, $start = null, $end = null){
        $query->select('populations.*', 'populations.id as value', 'populations.name as label')
            ->leftjoin('population_festivals', 'population_festivals.population_id', '=', 'populations.id');
        if ($start && $end){
            $query->whereBetween('population_festivals.day', [$start, $end]);
        }
        $query->groupBy('populations.id');
        return $query;
    }
}
