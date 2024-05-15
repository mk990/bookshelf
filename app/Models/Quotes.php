<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="QuoteModel",
 *     title="Quote Model",
 *     description="Represents a Quote",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int32",
 *         description="Quote ID"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="name"
 *     ),
 *     @OA\Property(
 *         property="quotation",
 *         type="string",
 *         description="quotation"
 *     ),
 * )
 */
class Quotes extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'quotation'];
}
