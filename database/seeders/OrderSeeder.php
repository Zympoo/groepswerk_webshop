<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Get or create some users
        $users = User::all();
        if ($users->isEmpty()) {
            $users = User::factory(3)->create();
        }

        // Create 5 orders
        for ($i = 0; $i < 5; $i++) {
            $order = Order::factory()->create([
                'user_id' => $users->random()->id,
            ]);

            // Create 1-4 items per order
            $details = OrderDetail::factory($this->faker()->numberBetween(1, 4))->create([
                'order_id' => $order->id,
            ]);

            // Update total amount on the order
            $order->update([
                'total_amount' => $details->sum('subtotal'),
            ]);
        }
    }

    private function faker()
    {
        return \Faker\Factory::create();
    }
}
