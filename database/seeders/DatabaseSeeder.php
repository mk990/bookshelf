<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Quotes;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        Book::factory(100)->create();

        User::factory()->create([
            'first_name' => 'mohammad',
            'last_name'  => 'hemmati',
            'email'      => 'test@example.com',
            'is_admin'   => true
        ]);
        Quotes::factory(5)->create();
    }
}
