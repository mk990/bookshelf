<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'category_id', 'title', 'author', 'price', 'picture'];

    public function user()
    {
        return $this->BelongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(category::class);
    }
}
