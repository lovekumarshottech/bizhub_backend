<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'image',
        'device_id',
        'device_type',
        'is_active',
        'is_verified',
        'password',
        'remember_token',
        'token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'device_type',
        'token',
        'forgot_token',
        'is_active',
        'is_verified',
        'is_admin',
        'email_verified_at',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }


    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function applications()
    {
        return $this->hasMany(ServiceApplication::class);
    }

    public function isAuthUserAppliedService() {
        return $this->applications()->where('user_id',  auth()->id())->exists();
    }


    public function isAuthUserOwnService() {
        return $this->services()->where('user_id',  auth()->id())->exists();
    }


    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


}
