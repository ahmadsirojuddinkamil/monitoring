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
    public static function createLogging($saveDir, $saveOther, $saveGeneral, $saveOwnerLog)
    {
        return self::create([
            'uuid' => Uuid::uuid4(),
            'connection_uuid' => $saveOwnerLog,
            'type' => $saveDir,
            'data' => $saveOther ?? null,
            'info' => $saveGeneral['info'] ?? null,
            'emergency' => $saveGeneral['emergency'] ?? null,
            'alert' => $saveGeneral['alert'] ?? null,
            'critical' => $saveGeneral['critical'] ?? null,
            'error' => $saveGeneral['error'] ?? null,
            'warning' => $saveGeneral['warning'] ?? null,
            'notice' => $saveGeneral['notice'] ?? null,
            'debug' => $saveGeneral['debug'] ?? null,
        ]);
    }
}
