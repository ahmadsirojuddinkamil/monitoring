<?php

namespace Modules\Connection\Database\Seeders;

use Modules\Connection\App\Models\Connection;
use Illuminate\Database\Seeder;

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
