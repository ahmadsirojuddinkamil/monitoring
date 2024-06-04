<?php

namespace Modules\User\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Comment\App\Models\Comment;
use Modules\Connection\App\Models\Connection;
use Modules\User\Database\factories\UserFactory;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'uuid',
        'log_uuid',
        'comment_uuid',
        'username',
        'email',
        'password',
        'profile',
        'status',
    ];

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    // Relationship
    public function comment()
    {
        // user_uuid: key in tabel comment || uuid: primary key in tabel user
        return $this->hasOne(Comment::class, 'user_uuid', 'uuid');
    }

    public function connection()
    {
        // user_uuid: key in tabel connection || uuid: primary key in tabel user
        return $this->hasOne(Connection::class, 'user_uuid', 'uuid');
    }
}
