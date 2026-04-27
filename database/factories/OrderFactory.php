<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'total_amount' => 0, // Will be calculated based on details
            'status' => $this->faker->randomElement(OrderStatus::cases()),
            'address_details' => [
                'first_name' => $this->faker->firstName,
                'last_name' => $this->faker->lastName,
                'street' => $this->faker->streetName,
                'house_number' => $this->faker->buildingNumber,
                'city' => $this->faker->city,
                'postal_code' => $this->faker->postcode,
                'country' => $this->faker->country,
            ],
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-1 month'),
        ];
    }
}
