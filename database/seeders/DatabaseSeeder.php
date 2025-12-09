<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PageContentSeeder::class,
            // DefaultContentSeeder is available but not run by default
            // To seed default content for demo/test purposes, run:
            // php artisan db:seed --class=DefaultContentSeeder
        ]);
    }
}
