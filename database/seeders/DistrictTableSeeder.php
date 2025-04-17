<?php
namespace Database\Seeders;

use App\Models\Definitions\District;
use Illuminate\Database\Seeder;

class DistrictTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \JsonException
     */
    public function run(): void
    {
        $path            = base_path('database/json/districts.json');
        $districtsJson   = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);

        $districts = (array)$districtsJson['districts'];

        for ($i = 0; $i <= 81; $i++) {
            foreach ($districts[$i] as $district) {
                $cityId = $i + 1;
                foreach ($district as $item) {
                    District::firstOrCreate([
                        'city_id' => $cityId,
                        'name'    => $item['name'],
                        'lat'     => $item['latitude'],
                        'lng'     => $item['longitude'],
                    ]);
                }
                $this->command->info('Districts of the '.$cityId.'st province loaded');
            }
        }
    }
}
