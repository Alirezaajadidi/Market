<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    use sluggable;

    protected $fillable = ['title', 'description', 'body', 'images', 'tags'];

    protected $casts = [
        'images' => 'array'
    ];

    public function scopeSearch($query, $keyword)
    {
        $query->whereHas('categories', function ($query) use ($keyword) {
            $query->where('name', 'LIKE', '%' . $keyword . '%');
        })
            ->orWhere('title', 'LIKE', '%' . $keyword . '%')
            ->orWhere('tags', 'LIKE', '%' . $keyword . '%');

        return $query;

    }


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

    public function path()
    {
        return "articles/$this->slug";
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function categories()
    {
        return $this->belongsTo(Category::class);
    }
}

