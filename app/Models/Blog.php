<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     schema="BlogModel",
 *     title="Blog Model",
 *     description="Represents a Blog",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int32",
 *         description="Book ID"
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="title"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="description ID"
 *     ),
 *     @OA\Property(
 *         property="article",
 *         type="string",
 *         description="article"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Creation date"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Update date"
 *     )
 * )
 */
class Blog extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'article', 'verified'];
}
