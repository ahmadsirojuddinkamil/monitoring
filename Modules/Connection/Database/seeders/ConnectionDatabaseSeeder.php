<?php

namespace Modules\Connection\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Connection\App\Models\Connection;

class ConnectionDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Connection::factory()->create();
    }
}
