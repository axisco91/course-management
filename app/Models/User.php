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
        'active'
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

    public static function getUsers(){
        $users = User::select('*', DB::raw("CONCAT(users.name,' ',users.surname) as label"),
            'users.id as value')
            ->get();
        return $users;
    }

    public static function getUser($id){
        $user = User::select('*', DB::raw("CONCAT(users.name,' ',users.surname) as label"),
            'users.id as value')
            ->where('users.id', $id)
            ->first();
        return $user;
    }

    public static function createUser($data){
        $user = User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'has_commission' => $data['has_commission'],
            'commission' => $data['commission'] ? $data['commission'] : 0.0,
            'active' => $data['active']
        ]);
        $roles = [$data['roles']];
        $user->syncRoles($roles);
        return $user;
    }

    public static function updateUser($id, $data){
        $user = User::find($id);
        $user->update([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'username' => $data['username'],
            'email' => $data['email'],
            'has_commission' => $data['has_commission'],
            'commission' => $data['commission'] ? $data['commission'] : 0.0,
            'active' => $data['active']
        ]);
        $roles = [$data['roles']];
        $user->syncRoles($roles);
        return $user;
    }

    public static function findDni($dni, $id = null){
        $user = User::where('dni', $dni);
        if ($id){
            $user = $user->where('id', '!=', $id);
        }
        $user = $user->first();

        return $user;
    }

    public static function findUser($user, $id = null){
        $user = User::where('username', $user);
        if ($id){
            $user = $user->where('id', '!=', $id);
        }
        $user = $user->first();

        return $user;
    }

    public static function getRoleNames(){
        return [];
    }
}
