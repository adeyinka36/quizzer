<?php

use App\enums\MembershipCost;
use App\enums\MembershipDuration;
use App\enums\MembershipType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('type', [MembershipType::FREE->value, MembershipType::PREMIUM->value])->default(MembershipDuration::FREE->value);

            $table->enum('price_cent',[MembershipCost::FREE->value, MembershipCost::PREMIUM->value])->default(MembershipCost::FREE->value);
            $table->enum('duration_months', [ MembershipDuration::FREE->value,  MembershipDuration::ONE_MONTH->value])->default(MembershipDuration::FREE->value);


            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};
