<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcludedDay extends Model
{
    use HasFactory;

    protected $fillable = ['day', 'general', 'main_company_id'];
}
