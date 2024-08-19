<?php

namespace Database\Seeders;

use App\Models\AccountSize;
use App\Models\Industry;
use App\Models\Source;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin')
        ]);

        $sources = [
            'Customer',
            'Known',
            'Social Media » LinkedIn',
            'Social Media » X',
            'Social Media » YouTube',
            'Event',
            'Referrer » Customer',
            'Referrer » Known',
            'Partner',
            'Community » PROductividad',
            'Web » Lead Magnet',
            'Ads',
            'Other'
        ];
        foreach ($sources as $source) {
            Source::create(['name' => $source]);
        }

        $accountSizes = [
            '1',
            '2 - 10',
            '11 - 50',
            '51 - 200',
            '201 - 500',
            '501 - 1.000',
            '1.001 - 5.000',
            '5.001 - 10.000',
            '+10.001'
        ];
        foreach ($accountSizes as $size) {
            AccountSize::create(['name' => $size]);
        }

        $industries = [
            'Public Administration',
            'Agricultural',
            'Commerce',
            'Construction',
            'Comunications',
            'Energy',
            'Finances',
            'Industry',
            'Investigation',
            'ONG',
            'Financial Services',
            'TIC',
            'Transports',
            'Tourism',
        ];
        foreach ($industries as $industry) {
            Industry::create(['name' => $industry]);
        }

        $this->call([
            LeadSeeder::class
        ]);
    }
}
