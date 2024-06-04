<?php

namespace Modules\Comment\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Comment\Database\factories\CommentFactory;
use Modules\User\App\Models\User;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'uuid',
        'user_uuid',
        'username',
        'comment',
    ];

    protected static function newFactory(): CommentFactory
    {
        return CommentFactory::new();
    }

    // Relationship
    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
