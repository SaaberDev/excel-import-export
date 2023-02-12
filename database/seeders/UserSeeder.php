<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::factory()->count(10)->make();
        $users->makeVisible(['password', 'remember_token']);
        $chunked = $users->chunk(500);

        foreach ($chunked->toArray() as $items) {
            foreach ($items as $item) {
                $item['created_at'] = now()->toDateTimeString();
                $item['updated_at'] = now()->toDateTimeString();
                User::query()->insert($item);
            }
        }
    }
}
