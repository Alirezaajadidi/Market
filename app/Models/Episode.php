<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpseclib3\Crypt\Hash;

class Episode extends Model
{

    use HasFactory;

    protected $primaryKey = 'course_id';


    protected $guarded = [];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function path()
    {
        return "/courses/{$this->course->slug}/episode/{$this->number}";
    }

    public function download()
    {
        if(! auth()->check()) return '#';

        $status = false;
        switch ($this->type) {
            case 'free' :
                $status = true;
                break;
            case 'vip' :
                if(auth()->user()->isActive()) $status = true;
                break;
            case 'cash' :
                if(auth()->user()->checkLearning($this->course)) $status = true;
                break;
        }
        $timestamp = Carbon::now()->addHours(5)->timestamp;
        $hash = \Illuminate\Support\Facades\Hash::make('fds@#T@#56@sdgs131fasfq' . $this->id . request()->ip() . $timestamp);

        return $status ? "/download/$this->id?mac=$hash&t=$timestamp" : "#";
    }

}
