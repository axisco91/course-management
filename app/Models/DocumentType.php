<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    public static function getTypes(){
        $documentType = DocumentType::select('*', 'id as value', 'name as label')->get();
        return $documentType;
    }
}
