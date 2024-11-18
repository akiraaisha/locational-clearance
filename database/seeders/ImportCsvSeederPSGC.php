<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImportCsvSeederPSGC extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $filePath = storage_path('app/PSGC_v2.csv');

        if (!file_exists($filePath) || !is_readable($filePath)) {
            echo "CSV file not found or is not readable.";
            return;
        }

        // Read the CSV file
        $header = null;
        $data = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (!$header) {
                    $header = array_map('trim', $row); // Trim any whitespace from headers
                    // Debugging: Print the headers
                    print_r($header); // Check the header names
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }


        //         Insert the data into the database
        foreach ($data as $row) {
            if ($row['Geographic_Level'] == '1') {
                // Debugging: Output the values being inserted
                echo "Inserting: " . json_encode([
                        'name' => $row['Name'],
                        'population' => str_replace(',', '', $row['Population']),
                        'code' => $row['Region_Code'],
                        'PSGC_Code' => $row['PSGC_Code'],
                    ]) . "\n";
                // Adjust this to match your table and column names
                //Insert Regions Data
                if ($row['Geographic_Level'] == '1') {
                    DB::table('regions')->insert([
                        'name' => $row['Name'], // Match CSV header names
                        'population' => $row['Population'],
                        'code' => $row['Region_Code'],
                        'PSGC_Code' => $row['PSGC_Code'],
                        // Add more fields as needed
                    ]);
                }
            }
        }
        //        echo "Regions data imported successfully.\n";
        echo "\e[0;32m" . "Region data imported successfully!\n" . "\e[0m\n";
        $rogueProvinceCount = 0;
        $insertedCitiesMun = 0;
        foreach ($data as $row) {
            if ($row['Geographic_Level'] == '2') {
                // Retrieve the region_id based on the Region_Code
                $region = DB::table('regions')->where('code', $row['Region_Code'])->first();

                if ($region) {

                    // Debugging: Output the values being inserted
//                    echo "Inserting: " . json_encode([
//                            'name' => $row['Name'],
//                            'population' => str_replace(',', '', $row['Population']),
//                            'code' => $row['Province_Code'],
//                            'PSGC_Code' => $row['PSGC_Code'],
//                            'region_id' => $region->id,
//                        ]) . "\n";

                    DB::table('Provinces')->insert([
                        'name' => $row['Name'],
                        'population' => str_replace(',', '', $row['Population']),
                        'code' => $row['Province_Code'],
                        'PSGC_Code' => $row['PSGC_Code'],
                        'region_id' => $region->id, // Use the retrieved region_id
                    ]);
                } else {
                    echo "Region with code " . $row['Region_Code'] . " not found.\n";
                }
            }

            //City and Municipality
            if ($row['Geographic_Level'] == '3' || $row['Geographic_Level'] == '4' || $row['Geographic_Level'] == '6') {

                // Retrieve the Province_id based on the CityMunicipality_Code
                $province = DB::table('provinces')->where('code', $row['Province_Code'])->first();

                if ($province) {
                    // Debugging: Output the values being inserted
//                    echo "Inserting: " . json_encode([
//                            'name' => $row['Name'],
//                            'population' => str_replace(',', '', $row['Population']),
//                            'code' => $row['CityMunicipality_Code'],
//                            'PSGC_Code' => $row['PSGC_Code'],
//                            'province_id' => $province->id,
//                        ]) . "\n";

                    DB::table('city_municipalities')->insert([
                        'name' => $row['Name'],
                        'population' => str_replace(',', '', $row['Population']),
                        'code' => $row['CityMunicipality_Code'],
                        'PSGC_Code' => $row['PSGC_Code'],
                        'province_id' => $province->id,// Use the retrieved region_id
                    ]);
                    $insertedCitiesMun++;
                } else {

                    DB::table('city_municipalities')->insert([
                        'name' => $row['Name'],
                        'population' => str_replace(',', '', $row['Population']),
                        'code' => $row['CityMunicipality_Code'],
                        'PSGC_Code' => $row['PSGC_Code'],
                        'province_id' => 'rogue',
                        //                        'province_id' => $province->id,// Use the retrieved region_id
                    ]);
                    echo "\e[1;33mNotice: " . "\e[0mInserting rogue City/Municipality: " . $row['Name'] . " - " . $row['PSGC_Code'] . "\n";
                    $rogueProvinceCount++;
                }
            }

        }

        echo "\n\e[1;33mTotal Rogue Cities/Municipalities: \e[0m" . $rogueProvinceCount . "\n";
        echo "\e[1;33mTotal Inserted Cities/Municipalities: \e[0m" . $insertedCitiesMun . "\n";
        echo "\e[1;32mTotal Cities/Municipalities: \e[0m" . $insertedCitiesMun + $rogueProvinceCount . "\n";
        //    //display echo with color green
        //    //        echo "\e[0;32m" . "Region data imported successfully.\n";
        //echo "\e[0;32m" . "Province data imported successfully!" . "\e[0m\n";

    }
}
