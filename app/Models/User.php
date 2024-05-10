<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @SWG\Definition(
 *     definition="User",
 *     type="array",
 *     @SWG\Items(
 *         type="object",
 *         @SWG\Property(type="string", property="user_name", description="User name"),
 *         @SWG\Property(type="array", property="education", description="Education",
 *             @SWG\Items(
 *                 @SWG\Property(property="degree", type="object",
 *                     type="array",
 *                     @SWG\Items(
 *                         @SWG\Property(property="year", type="string"),
 *                         @SWG\Property(property="name", type="string"),
 *                     ),
 *                 ),
 *                 @SWG\Property(property="hobby", type="object",
 *                     type="array",
 *                     @SWG\Items(
 *                         @SWG\Property(property="type", type="string"),
 *                         @SWG\Property(property="description", type="string"),
 *                     ),
 *                 ),
 *             ),
 *         ),
 *     ),
 * ),
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
