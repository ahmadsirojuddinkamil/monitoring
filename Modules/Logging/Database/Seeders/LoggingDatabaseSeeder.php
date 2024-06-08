<?php

namespace Modules\Logging\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Logging\App\Models\Logging;

class LoggingDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);
        Logging::factory()->count(4000)->create();
    }
}
