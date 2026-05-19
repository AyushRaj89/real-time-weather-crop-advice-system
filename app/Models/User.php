<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'default_city',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_admin'          => 'boolean',
    ];

    /**
     * A user has many weather log entries.
     */
    public function weatherLogs()
    {
        return $this->hasMany(WeatherLog::class);
    }

    /**
     * Get the most recent weather log for this user.
     */
    public function latestWeather()
    {
        return $this->hasOne(WeatherLog::class)->latestOfMany();
    }

    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }
}