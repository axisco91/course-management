<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityFestival extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['day', 'name', 'community_id'];

}
