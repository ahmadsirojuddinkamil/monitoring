<?php

namespace Modules\Logging\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Connection\App\Models\Connection;
use Modules\Logging\Database\factories\LoggingFactory;
use Ramsey\Uuid\Uuid;

class Logging extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'uuid',
        'connection_uuid',
        'type',
        'data',
        'emergency',
        'alert',
        'critical',
        'error',
        'warning',
        'notice',
        'info',
        'debug',
    ];

    // Factory
    protected static function newFactory(): LoggingFactory
    {
        return LoggingFactory::new();
    }

    // Relationship
    public function connection()
    {
        return $this->belongsTo(Connection::class);
    }

    // Query
    public static function createLogging($saveDir, $saveOther, $savePath, $saveOwnerLog)
    {
        return self::create([
            'uuid' => Uuid::uuid4(),
            'connection_uuid' => $saveOwnerLog,
            'type' => $saveDir,
            'data' => $saveOther ?? null,
            'info' => $savePath['info'] ?? null,
            'emergency' => $savePath['emergency'] ?? null,
            'alert' => $savePath['alert'] ?? null,
            'critical' => $savePath['critical'] ?? null,
            'error' => $savePath['error'] ?? null,
            'warning' => $savePath['warning'] ?? null,
            'notice' => $savePath['notice'] ?? null,
            'debug' => $savePath['debug'] ?? null,
        ]);
    }
}
