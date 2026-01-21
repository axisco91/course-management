<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'password',
        'url',
        'logo',

        // 🎨 colores
        'primary_color',
        'secondary_color',
        'success_color',
        'warning_color',
        'error_color'
    ];
}
