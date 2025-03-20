<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;


    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'surname',
        'username',
        'has_commission',
        'commission',
        'profile_photo_path',
        'active',
        'teacher_id',
        'advisor_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Variable que recoge los campos por los que filtrar
     * @var string[]
     */
    protected $allowIncluded = [

    ];

    /**
     * Variable que recoge los campos por los que vamos a ordenar
     * @var string[]
     */
    protected $allowSort = [
        'id',
        'name',
        'username'
    ];

    protected $allowFilter = [
        'id',
        'name',
        'surname',
    ];

    public function scopeGetUser($query) {
        return $query->select('users.*', DB::raw("CONCAT(users.name,' ',users.surname) as label"),
            'users.id as value', DB::raw("CONCAT(teachers.name,' ',teachers.surname) as teacher"))
            ->leftjoin('teachers', 'teachers.id', '=', 'users.teacher_id');
    }

    public function commissions()
    {
        return $this->hasMany(UserCommission::class);
    }

    public static function updateUser($id, $data){
        $user = User::find($id);
        $user->update([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'username' => $data['username'],
            'email' => $data['email'],
            'has_commission' => $data['has_commission'],
            'commission' => $data['commission'] ?? 0.0,
            'active' => $data['active'],
            'teacher_id' => $data['teacher_id'] ?? null,
        ]);
        $roles = [$data['roles']];
        $user->syncRoles($roles[0]);
        return $user;
    }
}
