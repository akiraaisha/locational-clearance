<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('locational_clearances', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('lot_number')->nullable();
            $table->string('block_number')->nullable();
            $table->string('street_number')->nullable();
            $table->string('street_name')->nullable();
            //Foreign keys for subdivison, barangay, city_municipality,province
            $table->foreignId('subdivision_id')->nullable()->constrained();
            $table->foreignId('barangay_id')->nullable()->constrained();
            $table->foreignId('city_municipalities_id')->nullable()->constrained();
            $table->string('zip_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locational_clearances');
    }
};
