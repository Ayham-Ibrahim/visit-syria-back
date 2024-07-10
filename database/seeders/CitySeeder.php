<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        City::create([
            'name' => "حمص"
        ]);
        City::create([
            'name' => "حماه"
        ]);
        City::create([
            'name' => "دمشق"
        ]);
        City::create([
            'name' => "حلب"
        ]);
        City::create([
            'name' => "تدمر"
        ]);
        City::create([
            'name' => "درعا"
        ]);
        City::create([
            'name' => "ادلب"
        ]);
        City::create([
            'name' => "الحسكة"
        ]);
        City::create([
            'name' => "دير الزور"
        ]);
        City::create([
            'name' => "اللاذقية"
        ]);
        City::create([
            'name' => "طرطوس"
        ]);
        City::create([
            'name' => "السويداء"
        ]);
    }
}
