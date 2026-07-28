<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebPlatformEmailTemplate extends Model
{
    protected $fillable = ['web_platform_id', 'mail_type', 'subject', 'body_html'];
}
