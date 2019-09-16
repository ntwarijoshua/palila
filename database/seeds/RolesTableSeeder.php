<?php

use App\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'slug' => 'manager',
            'description' => 'The Person Responsible for creating and managing the event'
        ]);
        Role::create([
            'slug' => 'visitor',
            'description' => 'A user who visits the site to browse events'
        ]);
    }
}
