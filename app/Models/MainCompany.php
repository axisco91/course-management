<?php

namespace App\Models;

use App\Services\MainCompanyService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MainCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'address',
        'phone',
        'email',
        'password',
        'url',
        'logo',
        'active',
        'title',

        // 🎨 colores
        'primary_color',
        'secondary_color',
        'success_color',
        'warning_color',
        'error_color'
    ];

    protected static function booted()
    {
        static::creating(function (self $mainCompany) {
            if (empty($mainCompany->uuid)) {
                $mainCompany->uuid = (string) Str::uuid();
            }
        });
    }

    public static function createWithService(array $data)
    {
        $service = app(MainCompanyService::class);

        return $service->create($data);
    }

    public function updateWithService(array $data)
    {
        $service = app(MainCompanyService::class);

        return $service->update($this, $data);
    }

    public function deleteWithService(): bool
    {
        $service = app(MainCompanyService::class);

        return $service->delete($this);
    }
}
