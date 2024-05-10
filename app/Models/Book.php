<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="BookModel",
 *     title="Book Model",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int32",
 *         description="id",
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer",
 *         format="int32",
 *         description="user id",
 *     ),
 *     @OA\Property(
 *         property="category_id",
 *         type="integer",
 *         format="int32",
 *         description="Category id",
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="title book",
 *     ),
 *     @OA\Property(
 *         property="author",
 *         type="string",
 *         description="writer book",
 *     ),
 *     @OA\Property(
 *         property="price",
 *         type="integer",
 *         format="int32",
 *         description="price",
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Creation date",
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Update date",
 *     ),
 * )
 *
 * @OA\Schema(
 *     schema="BookList",
 *     title="Book List",
 *     @OA\Property(
 *         property="current_page",
 *         type="integer",
 *         format="int32",
 *         description="Current page number",
 *     ),
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/BookModel"),
 *         description="List of books",
 *     ),
 *     @OA\Property(
 *         property="first_page_url",
 *         type="string",
 *         format="uri",
 *         description="First page URL",
 *     ),
 *     @OA\Property(
 *         property="from",
 *         type="integer",
 *         format="int32",
 *         description="First item number in the current page",
 *     ),
 *     @OA\Property(
 *         property="last_page",
 *         type="integer",
 *         format="int32",
 *         description="Last page number",
 *     ),
 *     @OA\Property(
 *         property="last_page_url",
 *         type="string",
 *         format="uri",
 *         description="Last page URL",
 *     ),
 *     @OA\Property(
 *         property="next_page_url",
 *         type="string",
 *         format="uri",
 *         description="Next page URL",
 *     ),
 *     @OA\Property(
 *         property="path",
 *         type="string",
 *         description="Path",
 *     ),
 *     @OA\Property(
 *         property="per_page",
 *         type="integer",
 *         format="int32",
 *         description="Items per page",
 *     ),
 *     @OA\Property(
 *         property="prev_page_url",
 *         type="string",
 *         format="uri",
 *         description="Previous page URL",
 *     ),
 *     @OA\Property(
 *         property="to",
 *         type="integer",
 *         format="int32",
 *         description="Last item number in the current page",
 *     ),
 *     @OA\Property(
 *         property="total",
 *         type="integer",
 *         format="int32",
 *         description="Total items",
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
