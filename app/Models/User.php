<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function today_attendance()
    {
        return $this->hasOne(Attendance::class, 'user_id')->whereDate('attendance_date', '=', date('Y-m-d'));
    }
    public function present()
    {
        return $this->hasMany(Attendance::class, 'user_id')->where('attendance_status',1);
    }
    public function absent()
    {
        return $this->hasMany(Attendance::class, 'user_id')->where('attendance_status',0);
    }
    public function working_hours()
    {
        return $this->hasMany(Attendance::class, 'user_id')->where('attendance_status',1)->sum('working_hours');
    }
    public function leave()
    {
        return $this->hasMany(Attendance::class, 'user_id')->whereIn('leave_status',['sick', 'casual']);
    }
}
