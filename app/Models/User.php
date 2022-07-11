<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'active',
        'api_token',
        'viptime'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function article()
    {
        return $this->hasMany(Article::class);
    }

    public function course()
    {
        return $this->hasMany(Course::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

//        foreach ($role as $r) {
//            if($this->hasRole($r->name)) {
//                return true;
//            }
//        }
//        return false;

        return !!$role->intersect($this->roles)->count();
    }

    public function isAdmin()
    {
        return $this->level == 'admin' ? true : false;
    }

    public function activationCodes()
    {
        return $this->hasMany(ActivationCode::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function checkLearning($course)
    {
        return !!Learning::where('user_id', $this->id)->where('course_id', $course->id)->first();
    }

    public function isActive()
    {
        return $this->viptime > Carbon::now() ? true : false;
    }

}
