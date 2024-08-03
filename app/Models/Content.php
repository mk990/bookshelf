<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="ContentModel",
 *     title="Content Model",
 *     description="Represents a content",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int32",
 *         description="Book ID"
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="integer",
 *         format="int32",
 *         description="User ID"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="integer",
 *         format="int32",
 *         description="Category ID"
 *     ),
 *     @OA\Property(
 *         property="content",
 *         type="string",
 *         description="Book title"
 *     ),
 *     @OA\Property(
 *         property="verify",
 *         type="boolean",
 *         description="Book author"
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
class Content extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'content', 'verified'];
}
