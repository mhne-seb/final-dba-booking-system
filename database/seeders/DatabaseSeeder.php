<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\ServiceDetails;
use App\Models\ServiceRequest;
use Carbon\Carbon;

class DashboardTestDataSeeder extends Seeder
{
    public function run()
    {
        $services = ['Tire Vulcanizing', 'Tire Replacement', 'Wheel Alignment', 'Wheel Balancing'];
        $statuses = ['pending', 'confirmed', 'in_queue', 'cancelled'];

        for ($i = 1; $i <= 15; $i++) {
            // 1. Create Customer
            $customer = Customer::create([
                'name' => "Test Customer $i",
                'phone_number' => "091700000" . ($i % 10),
                'email' => "customer$i@example.com",
                'created_since' => now(),
            ]);

            // 2. Create Vehicle
            $vehicle = Vehicle::create([
                'customer_id' => $customer->customer_id,
                'vehicle_type' => 'SUV',
                'brand' => 'Toyota',
                'model' => 'Fortuner',
                'plate_number' => "TEST-$i" . "ABC",
            ]);

            // 3. Create Service Details with varied dates for the Weekly Chart
            // This spreads requests over the last 7 days
            $randomDate = Carbon::today()->subDays(rand(0, 6));
            
            $details = ServiceDetails::create([
                'preferred_date' => $randomDate,
                'preferred_time' => '10:00:00',
                'service_type' => $services[array_rand($services)],
                'description' => 'Periodic maintenance check.',
            ]);

            // 4. Create Service Request
            ServiceRequest::create([
                'customer_id' => $customer->customer_id,
                'vehicle_id' => $vehicle->vehicle_id,
                'shop_id' => 1,
                'service_details_id' => $details->service_details_id,
                'status' => $statuses[array_rand($statuses)],
                'created_at' => $randomDate->setHour(rand(8, 17)),
            ]);
        }
    }
}