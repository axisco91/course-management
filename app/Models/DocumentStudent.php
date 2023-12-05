<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentStudent extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'document_id', 'training_contract_id', 'name', 'document_name', 'key', 'signed', 'date_signed'];

}
