<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\LeadStatus;
use App\Models\Partner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class LeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $partners = [
            'Partner Corp',
            'Partner Inc'
        ];
        foreach ($partners as $partner) {
            Partner::create(['name' => $partner]);
        }

        $statuses = [
            'Nurturing',
            'Analyze',
            'Working',
            'Discovery Call',
            'Referred',
            'Discard'
        ];
        foreach ($statuses as $status) {
            LeadStatus::create(['name' => $status]);
        }

        if ( App::environment('local')) {
            Lead::factory(200)->create();
        }

    }
}
