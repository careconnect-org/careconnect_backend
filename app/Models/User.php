<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Support\Facades\Hash;


class User extends Model implements AuthenticatableContract
{
    use HasFactory, Notifiable, Authenticatable;

    protected $connection = 'mongodb';
    protected $collection = 'users';

    protected $fillable = [
        'username',
        'firstName',
        'lastName',
        'names',
        'image',
        'bio',
        'role',
        'address',
        'phoneNumber',
        'dateOfBirth',
        'email',
        'gender',
        'password',
        'otpExpires',
        'otp',
        'verified',
    ];

    protected $hidden = [
        'password',
        'otp',
    ];

    protected $casts = [
        'verified' => 'boolean',
        'dateOfBirth' => 'date',
    ];

  
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

  
    public static function login($email, $password)
    {
        $user = self::where('email', $email)->first();

        if (!$user) {
            throw new \Exception('Incorrect email');
        }

        if (!Hash::check($password, $user->password)) {
            throw new \Exception('Incorrect password');
        }

        return $user;
    }

 
    public static function validationRules()
    {
        return [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ];
    }


    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'user_id', '_id');
    }

   
    public function patient()
    {
        return $this->hasOne(Patient::class, 'user_id', '_id');
    }
}
