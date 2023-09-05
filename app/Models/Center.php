<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Center extends Model
{
	use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name','address','email','telephone'];

    public function scopeGetCenter($query) {
        return $query->select('centers.*', 'id as value', 'name as label');
    }
}
