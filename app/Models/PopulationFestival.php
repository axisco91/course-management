<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PopulationFestival extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['day', 'name', 'population_id'];

}
