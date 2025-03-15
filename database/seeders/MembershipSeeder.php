<?php

namespace Database\Seeders;

use App\enums\MembershipCost;
use App\enums\MembershipDuration;
use App\enums\MembershipType;
use App\Models\Membership;
use Illuminate\Database\Seeder;

class MembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $membershipTypes = [
            [
                'type' => MembershipType::FREE->value,
                'price_cent' => MembershipCost::FREE->value,
                'duration_months' => MembershipDuration::FREE->value
            ],
            [
                'type' => MembershipType::PREMIUM->value,
                'price_cent' => MembershipCost::PREMIUM->value,
                'duration_months' => MembershipDuration::ONE_MONTH->value
            ]
        ];
        foreach ($membershipTypes as $membershipType) {
            MemberShip::create($membershipType);
        }
    }
}
