<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'age',
        'adress',
        'governorate',
        'google_id'
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


    public function profile_teacher()
    {
        return $this->hasOne(ProfileTeacher::class,'user_id','id');
    }

    public function request_teacher()
    {
        return $this->hasOne(RequestComplete::class,'user_id','id');
    }

    public function teaching_methods()
    {
        return $this->hasMany(TeachingMethod::class,'user_id','id');
    }

    public function service_teachers()
    {
        return $this->hasMany(ServiceTeacher::class,'user_id','id');
    }

    public function profile_student()
    {
        return $this->hasOne(ProfileStudent::class,'user_id','id');
    }

    public function ads()
    {
        return $this->hasMany(Ads::class,'user_id','id');
    }

    public function appointment_teacher_students(){
        return $this->belongsToMany(
            AppointmentTeacherStudent::class,
            'appointment_teacher_students',
            'user_id',
            'teacher_id')->withPivot('id');
    }

    // public function appointment_teacher_students(){
    //     return $this->belongsToMany(
    //         AppointmentTeacherStudent::class,
    //         'appointment_teacher_students',
    //         'teacher_id',
    //         'user_id')->withPivot('id');
    // }



    public function setPasswordAttribute($value)
     {
         $this->attributes['password']=Hash::make($value);
     }


    public function getJWTIdentifier() {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }
}
