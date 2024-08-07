<?php

namespace Modules\Connection\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Connection\Database\factories\ClientConnectionFactory;
use Modules\Connection\Database\factories\ConnectionFactory;
use Modules\Logging\App\Models\Logging;
use Modules\User\App\Models\User;
use Ramsey\Uuid\Uuid;

class Connection extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'uuid',
        'user_uuid',
        'endpoint',
        'register',
        'login',
        'get_log',
        'get_log_by_type',
        'get_log_by_time',
        'delete_log',
        'delete_log_by_type',
        'delete_log_by_time',
        'token',
    ];

    // Factory
    protected static function newFactory(): ConnectionFactory
    {
        return ConnectionFactory::new();
    }

    protected static function clientFactory(): ClientConnectionFactory
    {
        return ClientConnectionFactory::new();
    }

    // Relationship
    public function user()
    {
        // user_uuid: untuk kolom meletakkan key target relasi || uuid: kolom target relasi yang valuenya disimpan di user_uuid
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }

    public function loggings()
    {
        return $this->hasMany(Logging::class, 'connection_uuid', 'uuid');
    }

    // Query
    public static function createConnection($saveUuidFromCall, $saveDataFromCall)
    {
        return self::create([
            'uuid' => Uuid::uuid4(),
            'user_uuid' => $saveUuidFromCall,
            'endpoint' => $saveDataFromCall['endpoint'],
            'register' => $saveDataFromCall['register'],
            'login' => $saveDataFromCall['login'],
            'get_log' => $saveDataFromCall['get_log'],
            'get_log_by_type' => $saveDataFromCall['get_log_by_type'],
            'get_log_by_time' => $saveDataFromCall['get_log_by_time'],
            'delete_log' => $saveDataFromCall['delete_log'],
            'delete_log_by_type' => $saveDataFromCall['delete_log_by_type'],
            'delete_log_by_time' => $saveDataFromCall['delete_log_by_time'],
            'token' => $saveDataFromCall['token'],
        ]);
    }

    public static function updateConnection($saveUuidFromCall, $saveDataFromCall)
    {
        $connection = self::where('uuid', $saveUuidFromCall)->first();

        return $connection->update([
            'endpoint' => $saveDataFromCall['endpoint'],
            'register' => $saveDataFromCall['register'],
            'login' => $saveDataFromCall['login'],
            'get_log' => $saveDataFromCall['get_log'],
            'get_log_by_type' => $saveDataFromCall['get_log_by_type'],
            'get_log_by_time' => $saveDataFromCall['get_log_by_time'],
            'delete_log' => $saveDataFromCall['delete_log'],
            'delete_log_by_type' => $saveDataFromCall['delete_log_by_type'],
            'delete_log_by_time' => $saveDataFromCall['delete_log_by_time'],
            'token' => $saveDataFromCall['token'],
        ]);
    }
}
