<?php

namespace Database\Seeders;

use App\Models\ProductionDepartament;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductionDepartamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductionDepartament::create([
            'name' => 'Pharmaceutical Formulation Department',
        ]);

        ProductionDepartament::create([
            'name' => 'Packaging and Labeling Division',
        ]);

        ProductionDepartament::create([
            'name' => 'Quality Assurance and Control Unit',
        ]);
    }
}
