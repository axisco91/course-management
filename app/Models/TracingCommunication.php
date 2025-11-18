<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TracingCommunication extends Model
{
	use HasFactory;

    protected $fillable = [
        'affair',
        'notes',
        'incidence_type_id',
        'tracing_id',
        'user_id',
        'main_company_id'
    ];

    public static function getTracingCommunications($tracing_id){
        $tracing_communications = TracingCommunication::select('*')->where('tracing_id', $tracing_id)->get();

        return $tracing_communications;
    }

    public static function createTracingCommunication($data){
        $tracing_communication = TracingCommunication::create(
            $data
        );

        return $tracing_communication;
    }

    public static function updateTracingCommunication($id, $data){
        $tracing_communication = TracingCommunication::find($id);
        $tracing_communication->update(
            $data
        );

        return $tracing_communication;
    }
}
