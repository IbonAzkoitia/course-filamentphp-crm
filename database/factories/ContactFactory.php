<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'birthday' => $this->faker->date(),
            'description' => $this->faker->text(),
            'job_title' => $this->faker->jobTitle(),
            'source_id' => $this->faker->numberBetween(1, 13),
            'url_linkedin' => $this->faker->url(),
            'url_website' => $this->faker->url(),
            'url_x' => $this->faker->url(),
            'street' => $this->faker->streetName(),
            'city' => $this->faker->city(),
            'state' => $this->faker->city(),
            'postcode' => $this->faker->randomNumber(5, true),
            'country' => $this->faker->country(),
            // 'account_id' => $this->faker->numberBetween(1, 100),
        ];
    }
}
