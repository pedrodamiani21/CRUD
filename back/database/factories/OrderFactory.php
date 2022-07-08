<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $customers = \DB::table('customers')->select('id')->get();
        $products = \DB::table('products')->select('id')->get();
        return [
            
            'customer_id' => fake()->randomElement($customers)->id,
            'product_id' =>  fake()->randomElement($products)->id,
            'order_date' =>  fake()->date(),
            'order_amount' => fake()->randomDigit(),
            'status' => fake()->text(10)
        ];
    }
}
