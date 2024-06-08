<?php

namespace Modules\Logging\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Connection\App\Models\Connection;
use Modules\Logging\Database\factories\LoggingFactory;

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
}
