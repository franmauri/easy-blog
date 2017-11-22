<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        //$this->truncateTables();
        \Artisan::call('cache:clear');
        $this->call(RolesPermissionsSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(PostsSeeder::class);

    }

    private function truncateTables() {
        $skip = [
            'migrations',
            'oauth_access_tokens',
            'oauth_auth_codes',
            'oauth_clients',
            'oauth_personal_access_clients',
            'oauth_refresh_tokens',
        ];

        $tableNames = DB::select('SHOW TABLES');

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ($tableNames as $name) {
            $name = array_values((array) $name)[0];
            //if you don't want to truncate migrations
            if (in_array($name, $skip)) {
                continue;
            }
            DB::table($name)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

}
