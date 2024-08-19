<?php

use App\Models\AccountSize;
use App\Models\Industry;
use App\Models\LeadStatus;
use App\Models\Partner;
use App\Models\Source;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('job_title')->nullable();
            $table->foreignIdFor(LeadStatus::class)->nullable()->constrained();
            $table->foreignIdFor(Source::class)->nullable()->constrained();
            $table->string('url_linkedin')->nullable();
            $table->string('url_website')->nullable();
            $table->string('url_x')->nullable();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postcode')->nullable();
            $table->string('country')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_revenue')->nullable();
            $table->foreignIdFor(AccountSize::class)->nullable()->constrained();
            $table->foreignIdFor(Industry::class)->nullable()->constrained();
            $table->foreignIdFor(Partner::class)->nullable()->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
