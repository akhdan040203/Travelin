<?php

namespace Database\Seeders;

use App\Models\Destination;
use App\Models\Schedule;
use Illuminate\Database\Seeder;

class AdditionalSchedulesSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['slug' => 'raja-ampat-paradise', 'days' => 10, 'quota' => 24, 'booked' => 8, 'meeting' => 'Bandara Domine Eduard Osok Sorong'],
            ['slug' => 'raja-ampat-paradise', 'days' => 31, 'quota' => 20, 'booked' => 4, 'meeting' => 'Pelabuhan Waisai'],
            ['slug' => 'bromo-sunrise-experience', 'days' => 14, 'quota' => 18, 'booked' => 9, 'meeting' => 'Stasiun Malang'],
            ['slug' => 'bromo-sunrise-experience', 'days' => 28, 'quota' => 22, 'booked' => 13, 'meeting' => 'Hotel Area Surabaya'],
            ['slug' => 'bali-island-hopping', 'days' => 17, 'quota' => 26, 'booked' => 11, 'meeting' => 'Bandara I Gusti Ngurah Rai'],
            ['slug' => 'taman-nasional-komodo', 'days' => 21, 'quota' => 16, 'booked' => 6, 'meeting' => 'Bandara Komodo Labuan Bajo'],
            ['slug' => 'yogyakarta-heritage-tour', 'days' => 24, 'quota' => 28, 'booked' => 10, 'meeting' => 'Stasiun Tugu Yogyakarta'],
            ['slug' => 'dieng-plateau-adventure', 'days' => 35, 'quota' => 20, 'booked' => 7, 'meeting' => 'Alun-Alun Wonosobo'],
        ];

        foreach ($items as $item) {
            $destination = Destination::where('slug', $item['slug'])->first();
            if (!$destination) {
                continue;
            }

            $departureDate = now()->addDays($item['days']);

            Schedule::firstOrCreate(
                [
                    'destination_id' => $destination->id,
                    'departure_date' => $departureDate->toDateString(),
                    'meeting_point' => $item['meeting'],
                ],
                [
                    'return_date' => $departureDate->copy()->addDays($destination->duration_days - 1)->toDateString(),
                    'quota' => $item['quota'],
                    'booked' => $item['booked'],
                    'price' => null,
                    'status' => 'open',
                ]
            );
        }
    }
}
