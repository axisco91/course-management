<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingContractSeries extends Model
{
    use HasFactory;

    protected $fillable = ['series', 'description'];
}