<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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

    public function getUsers($keyWord){
        $users = User::latest()
            ->orWhere('name', 'LIKE', $keyWord)
            ->orWhere('surname', 'LIKE', $keyWord)
            ->orWhere('username', 'LIKE', $keyWord)
            ->orWhere('email', 'LIKE', $keyWord)
            ->paginate(10);
        return $users;
    }

    public function createUser($data){
        $user = User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
        $user->syncRoles($data['role_id']);
        return $user;
    }

    public function updateUser($id, $data){
        $user = User::find($id);
        $user->update([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'username' => $data['username'],
            'email' => $data['email'],
        ]);
        //$user->syncRoles($data['role_id']);
        return $user;
    }

    public function findDni($dni, $id = null){
        $user = User::where('dni', $dni);
        if ($id){
            $user = $user->where('id', '!=', $id);
        }
        $user = $user->first();

        return $user;
    }

    public function findUser($user, $id = null){
        $user = User::where('username', $user);
        if ($id){
            $user = $user->where('id', '!=', $id);
        }
        $user = $user->first();

        return $user;
    }

    public function getRoleNames(){
        return [];
    }
}
