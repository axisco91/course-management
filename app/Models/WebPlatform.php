<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebPlatform extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name','url','code'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trainingActions()
    {
        return $this->hasMany('App\Models\TrainingAction', 'web_platform_id', 'id');
    }

    public static function getWebPlatforms(){
        $web_platforms = WebPlatform::
        select('*', 'id as value', 'name as label')
            ->get();
        foreach ($web_platforms as $web_platform){
            $trainingAction = TrainingAction::where('web_platform_id', $web_platform['id'])->first();
            if ($trainingAction){
                $web_platform['used'] = true;
            } else {
                $web_platform['used'] = false;
            }
        }
        return $web_platforms;
    }

    public static function getWebPlatform($id){
        $web_platform = WebPlatform::
        select('*', 'id as value', 'name as label')
            ->where('id', $id)
            ->first();
        $trainingAction = TrainingAction::where('web_platform_id', $web_platform['id'])->first();
        if ($trainingAction){
            $web_platform['used'] = true;
        } else {
            $web_platform['used'] = false;
        }
        return $web_platform;
    }

    public static function createWebPlatform($data){
        $web_platform = WebPlatform::create([
            'name' => $data['name'],
            'url' => $data['url'],
            'code' => $data['code']
        ]);
        return $web_platform;
    }

    public static function updateWebPlatform($id, $data){
        $web_platform = WebPlatform::find($id);
        $web_platform->update([
            'name' => $data['name'],
            'url' => $data['url'],
            'code' => $data['code']
        ]);
        return $web_platform;
    }

}
