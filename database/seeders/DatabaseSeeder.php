<?php

namespace Database\Seeders;

use App\Models\User;
use App\Utils\UserTypeUtil;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         User::factory(3)->create(['type' => UserTypeUtil::ADMIN]);
         User::factory(10)->create(['type' => UserTypeUtil::USER]);
    }
}
