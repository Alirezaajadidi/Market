<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use http\Env\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;
    use sluggable;


    protected $guarded = [];

    protected $casts = [
        'images' => 'array'
    ];


    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function setBodyAttribute($value)
    {
        $this->attributes['description'] = Str::limit(preg_replace('/<[^>]*>/', '', $value), 200);
        $this->attributes['body'] = $value;
    }

    public function path()
    {
        return "/courses/$this->slug";
    }

    public function episodes()
    {
        return $this->hasMany(Episode::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);

    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }


    public function scopeFilter($query)
    {
//        $course = Course::with('categories');

        $categories = request('category');
        if (request()->has('categories')) {
            $query->whereHas('categories', function ($query) use ($categories) {
                $query->whereId($categories);
            });
        }
        dd($categories);
    }

}
//->orWhereHas('state', function ($query) use ($request) {
//    $query->where('name', $request->name);
//});
