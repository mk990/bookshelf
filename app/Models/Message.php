<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="MessageModel",
 *     title="Ticket Model",
 *     description="Represents a ticket",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int32",
 *         description="message ID"
 *     ),
 *     @OA\Property(
 *         property="ticket_id",
 *         type="integer",
 *         format="int32",
 *         description="user id for ticket"
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer",
 *         format="int32",
 *         description="user id for ticket"
 *     ),
 *     @OA\Property(
 *         property="message",
 *         type="string",
 *         description="your message"
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
class Message extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'ticket_id', 'message'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
