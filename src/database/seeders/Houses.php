<?php

namespace Database\Seeders;

use App\Models\House;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class Houses extends Seeder
{
    public function run(): void
    {
        if (!$data = Storage::disk('local')->get('property-data.csv')) {
            $this->command->error('Could not open CSV to import');

            return;
        }

        $keys = ['title', 'price', 'bedrooms', 'bathrooms', 'storeys', 'garages'];
        $rows = array_slice(explode(PHP_EOL, $data), 1);
        foreach ($rows as $row) {
            $values = array_combine($keys, str_getcsv($row));
            House::query()->updateOrCreate(['title' => $values['title']], $values);
        }
        $this->command->info("There are " . House::query()->count() . ' houses!');
    }
}
