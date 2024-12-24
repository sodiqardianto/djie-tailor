<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Sahila',
                'email' => 'sahila@gmail.com',
                'phone_number' => '08123456789',
                'address' => 'Jl. Raya No. 1',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
