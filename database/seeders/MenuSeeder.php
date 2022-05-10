<?php

namespace Database\Seeders;

use Efectn\Menu\Models\Menus;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Menus::where('name', 'navbar')->count() == 0) {
            $nav = new Menus();
            $nav->name = 'navbar';
            $nav->save();
        }

        if (Menus::where('name', 'footer')->count() == 0) {
            $footer = new Menus();
            $footer->name = 'footer';
            $footer->save();
        }
    }
}
