<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 * @OA\Schema(
 * @OA\Xml(name="Post"),
 * @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="user_id", type="integer", readOnly="true", description="owner of post", example="2"),
 * @OA\Property(property="title", type="string", maxLength=32, example="Post Title Example"),
 * @OA\Property(property="body", type="string", maxLength=256, example="Post Body Example"),
 * )
 *
 * Class Post
 *
 */
class Post extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
