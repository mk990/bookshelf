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
 *         description="ticket ID"
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="title your ticket"
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
 *         property="stars",
 *         type="integer",
 *         format="int32",
 *         description="your rate in ticket"
 *     ),
 *     @OA\Property(
 *         property="last_message",
 *         type="string",
 *         format="date-time",
 *         description="last message date"
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
        'user_id', 'open', 'title', 'stars',
        'last_message'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function message()
    {
        return $this->hasMany(Message::class);
    }
}
