<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
   * @OA\Schema(
   *     schema="EbookModel",
   *     title="Ebook Model",
   *     description="Represents a Pdf",
   *     @OA\Property(
   *         property="id",
   *         type="integer",
   *         format="int32",
   *         description="Book ID"
   *     ),
   *     @OA\Property(
   *         property="user_id",
   *         type="integer",
   *         format="int32",
   *         description="user ID"
   *     ),
   *     @OA\Property(
   *         property="type",
   *         type="string",
   *         description="type"
   *     ),
   *     @OA\Property(
   *         property="author",
   *         type="string",
   *         description="Book author"
   *     ),
   *     @OA\Property(
   *         property="price",
   *         type="integer",
   *         format="int32",
   *         description="Book price"
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
class Ebook extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'type'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
