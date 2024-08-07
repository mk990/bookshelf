<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *     schema="CommentModel",
 *     title="comments Model",
 *     description="Represents a comment",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int32",
 *         description="comment ID"
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer",
 *         format="int32",
 *         description="User ID"
 *     ),
 *     @OA\Property(
 *         property="book_id",
 *         type="integer",
 *         format="int32",
 *         description="book id"
 *     ),
 *     @OA\Property(
 *         property="text",
 *         type="string",
 *         description="comment text"
 *     ),
 *     @OA\Property(
 *         property="verify",
 *         type="boolean",
 *         description="comment status"
 *     ),
 *     @OA\Property(
 *         property="stars",
 *         type="integer",
 *         format="int32",
 *         description="comment stars"
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
class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'book_id',
        'text',
        'stars'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
