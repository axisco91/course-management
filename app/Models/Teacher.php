<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    /**
     * Atributos de teacher
     */
    public $timestamps = false;
    protected $fillable = [
        'id',
        'name',
        'surname',
        'dni',
        'email',
        'telephone',
        'user',
        'password',
        'observations'
    ];
}
