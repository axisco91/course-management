<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebPlatform extends Model
{
	use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name','url'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trainingActions()
    {
        return $this->hasMany('App\Models\TrainingAction', 'web_platform_id', 'id');
    }

    public function getWebPlatforms($keyWord){
        $web_platforms = WebPlatform::
        orWhere('name', 'LIKE', $keyWord)
            ->orWhere('url', 'LIKE', $keyWord)
            ->paginate(10);
        foreach ($web_platforms as $web_platform){
            $training_action = TrainingAction::where('web_platform_id', $web_platform['id'])->first();
            if ($training_action){
                $web_platform['used'] = true;
            } else {
                $web_platform['used'] = false;
            }
        }
        return $web_platforms;
    }

    public function createWebPlatform($data){
        $web_platform = WebPlatform::create([
            'name' => $data['name'],
            'url' => $data['url']
        ]);
        return $web_platform;
    }

    public function updateWebPlatform($id, $data){
        $web_platform = WebPlatform::find($id);
        $web_platform->update([
            'name' => $data['name'],
            'url' => $data['url']
        ]);
        return $web_platform;
    }

}
