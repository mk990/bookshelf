<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
* @OA\Schema(
*     schema="BookModel",
*     title="Book Model",
*     type="object",
*     description="Book Model",
*     @OA\Property(
*         property="id",
*         description="Book id",
*         type="integer",
*         format="int32",
*     ),
*     @OA\Property(
*         property="user_id",
*         description="user_id",
*         type="integer",
*         format="int32",
*     ),
*     @OA\Property(
*         property="category_id",
*         description="category_id",
*         type="integer",
*         format="int32",
*     ),
*     @OA\Property(
*         property="title",
*         description="title",
*         type="string",
*         format="string",
*     ),
*     @OA\Property(
*         property="author",
*         description="author",
*         type="string",
*         format="string",
*     ),
*     @OA\Property(
*         property="price",
*         description="price",
*         type="integer",
*         format="int32",
*     ),
*     @OA\Property(
*         property="picture",
*         description="picture",
*         type="string",
*         format="string",
*     ),
*     @OA\Property(
*         property="created_at",
*         description="created_at",
*         type="string",
*         format="string",
*     ),
*     @OA\Property(
*         property="updated_at",
*         description="updated_at",
*         type="string",
*         format="string",
*     ),

* )
*/
class Book extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'category_id', 'title', 'author', 'price', ''];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
