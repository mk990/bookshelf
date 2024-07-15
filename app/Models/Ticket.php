<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="TicketModel",
 *     title="Ticket Model",
 *     description="Represents a ticket",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int32",
 *         description="Book ID"
 *     ),
 *     @OA\Property(
 *         property="open",
 *         type="boolean",
 *         description="open is ticket or not"
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer",
 *         format="int32",
 *         description="user id for ticket"
 *     ),
 *     @OA\Property(
 *         property="book_id",
 *         type="integer",
 *         format="int32",
 *         description="Book id for ticket"
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
class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'open', 'user_id', 'book_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function message()
    {
        return $this->hasOne(Message::class);
    }
}
