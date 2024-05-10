<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="BookModel",
 *     title="Book Model",
 *     description="Represents a book",
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
 *         description="User ID"
 *     ),
 *     @OA\Property(
 *         property="category_id",
 *         type="integer",
 *         format="int32",
 *         description="Category ID"
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Book title"
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
 *
 * @OA\Schema(
 *     schema="BookPrevious",
 *     title="Book Previous",
 *     description="Represents a link to a book",
 *     @OA\Property(
 *         property="url",
 *         type="string",
 *         description="Link URL",
 *         example=null
 *     ),
 *     @OA\Property(
 *         property="label",
 *         type="string",
 *         description="Link label",
 *         example="&laquo; Previous"
 *     ),
 *     @OA\Property(
 *         property="active",
 *         type="boolean",
 *         description="Indicates whether the link is active"
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="BookLinks",
 *     title="Book Links ",
 *     description="Represents an active link to a book",
 *     @OA\Property(
 *         property="url",
 *         type="string",
 *         description="Link URL",
 *         example="http://your-url/api/category?page=0"
 *     ),
 *     @OA\Property(
 *         property="label",
 *         type="string",
 *         description="Link label",
 *         example="1"
 *     ),
 *     @OA\Property(
 *         property="active",
 *         type="boolean",
 *         description="Indicates whether the link is active",
 *         example=true
 *     )
 * )
 * @OA\Schema(
 *     schema="BookNext",
 *     title="BookNext",
 *     description="Represents an active link to a book",
 *     @OA\Property(
 *         property="url",
 *         type="string",
 *         description="Link URL",
 *         example=null
 *     ),
 *     @OA\Property(
 *         property="label",
 *         type="string",
 *         description="Link label",
 *         example="Next &raquo;"
 *     ),
 *     @OA\Property(
 *         property="active",
 *         type="boolean",
 *         description="Indicates whether the link is active",
 *         example=false
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="BookList",
 *     title="Book List",
 *     description="Represents a list of books",
 *     @OA\Property(
 *         property="current_page",
 *         type="integer",
 *         format="int32",
 *         description="Current page number"
 *     ),
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/BookModel"),
 *         description="List of books"
 *     ),
 *     @OA\Property(
 *         property="first_page_url",
 *         type="string",
 *         format="uri",
 *         description="First page URL"
 *     ),
 *     @OA\Property(
 *         property="from",
 *         type="integer",
 *         format="int32",
 *         description="First item number in the current page"
 *     ),
 *     @OA\Property(
 *         property="last_page",
 *         type="integer",
 *         format="int32",
 *         description="Last page number"
 *     ),
 *     @OA\Property(
 *         property="links",
 *         type="array",
 *         @OA\Items(
 *             oneOf={
 *                 @OA\Schema(ref="#/components/schemas/BookPrevious"),
 *                 @OA\Schema(ref="#/components/schemas/BookLinks"),
 *                 @OA\Schema(ref="#/components/schemas/BookNext")
 *             }
 *         ),
 *         description="Links to books"
 *     ),
 *     @OA\Property(
 *         property="last_page_url",
 *         type="string",
 *         format="uri",
 *         description="Last page URL"
 *     ),
 *     @OA\Property(
 *         property="next_page_url",
 *         type="string",
 *         format="uri",
 *         description="Next page URL"
 *     ),
 *     @OA\Property(
 *         property="path",
 *         type="string",
 *         description="Path"
 *     ),
 *     @OA\Property(
 *         property="per_page",
 *         type="integer",
 *         format="int32",
 *         description="Items per page"
 *     )
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
