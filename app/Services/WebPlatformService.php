<?php

namespace App\Services;

use App\Models\WebPlatform;

class WebPlatformService
{
    /**
     * Función para crear una comisión de una asesoría
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
         return WebPlatform::create([
            'name' => $data['name'],
            'url' => $data['url'],
            'token' => $data['token'],
             'main_company_id' => $data['main_company_id']
        ]);
    }

    public function update(WebPlatform $webPlatform, array $data)
    {
        $webPlatform->update([
            'name' => $data['name'],
            'url' => $data['url'],
            'token' => $data['token']
        ]);
        return $webPlatform;
    }
}
