<?php

namespace Database\Seeders;

use App\Models\HomePageSetting;
use App\Models\Option;
use Illuminate\Database\Seeder;

class HomePageSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'home__main_title' => 'Pick wisely with us by your side',
            'home__main_description' => 'We’ve published over 194 buying guides and product reviews in these categories. Almost 700,000 words of content, 9000+ hours of work. Does that make us an expert in these fields? Read to judge',
            'home__how_it_work' => 'How it works',
            'home__how_it_work_icon_1' => 'icon-1.png',
            'home__how_it_work_icon_2' => 'icon-2.png',
            'home__how_it_work_icon_3' => 'icon-3.png',
            'home__how_it_work_icon_4' => 'icon-4.png',
            'home__how_it_work_title_1' => 'Market Research',
            'home__how_it_work_title_2' => 'Analysing Reviews',
            'home__how_it_work_title_3' => 'Ranking Products',
            'home__how_it_work_title_4' => 'Writing Guide',
            'home__how_it_work_desc_1' => 'Nullam a sem egestas, tristique elit at, bibendum ligula. Pellentesque faucibus quis risus non interdum. Nunc auctor.',
            'home__how_it_work_desc_2' => 'Nullam a sem egestas, tristique elit at, bibendum ligula. Pellentesque faucibus quis risus non interdum. Nunc auctor.',
            'home__how_it_work_desc_3' => 'Nullam a sem egestas, tristique elit at, bibendum ligula. Pellentesque faucibus quis risus non interdum. Nunc auctor.',
            'home__how_it_work_desc_4' => 'Nullam a sem egestas, tristique elit at, bibendum ligula. Pellentesque faucibus quis risus non interdum. Nunc auctor.',
	        'home__search_box_label' => 'Search Here',
	        'home__latest_post_label' => 'Latest Posts',
	        'home__explore_categories_label' => 'Explore Our Categories',
        ];

        foreach ($data as $key => $value) {
            $key = str_replace('__', '.', $key);
            $setting = Option::firstOrCreate(['name' => $key]);
            $setting->value = $value;
            $setting->save();
        }
    }
}
