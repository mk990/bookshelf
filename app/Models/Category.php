<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
* @OA\Schema(
*     schema="CategoryModel",
*     title="Category Model",
*     type="object",
*     description="Category Model",
*     @OA\Property(
*         property="id",
*         description="Category id",
*         type="integer",
*         format="int32",
*     ),
*     @OA\Property(
*         property="name",
*         description="user_id",
*         type="string",
*     ),
*     @OA\Property(
*         property="description",
*         description="description",
*         type="string",
*     ),
* )
*/
class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description'];

    public function Categorys()
    {
        return $this->hasMany(Category::class);
    }
}
