<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AssetFactory extends Factory
{
    public function definition(): array
    {
        return [
            'asset_code' => 'AST-' . fake()->unique()->numberBetween(1000, 9999),

            'asset_name' => fake()->randomElement([
                'Laptop Office',
                'Printer Epson',
                'Router Mikrotik',
            ]),

            'category' => fake()->randomElement([
                'Laptop',
                'Printer',
                'Networking',
            ]),

            'brand' => fake()->company(),

            'model' => fake()->bothify('Model-###'),

            'serial_number' => fake()->unique()->bothify('SN-#####'),

            'specification' => [
                'ram' => '8GB',
                'storage' => '512GB SSD',
            ],

            'purchase_date' => now(),

            'purchase_price' => fake()->numberBetween(3000000, 15000000),

            'condition' => 'good',

            'status' => 'active',

            'location' => 'Office Medan',
        ];
    }
}