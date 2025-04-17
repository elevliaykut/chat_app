<?php

namespace Database\Seeders;

use App\Models\Definitions\City;
use Illuminate\Database\Seeder;

class CityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \JsonException
     */
    public function run(): void
    {
        $path       = base_path('database/json/cities.json');
        $citiesJson = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);

        $cities = collect($citiesJson['cities']);
        $cities = $cities->sortBy('id');

        foreach ($cities as $city) {
            City::query()->firstOrCreate([
                'name' => $city['name'],
                'lat'  => $city['latitude'],
                'lng'  => $city['longitude'],
            ]);
            $this->command->info($city['name']. ' City IMPORTED');
        }
    }
}
