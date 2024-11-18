<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('PSGCs', function (Blueprint $table) {
			$table->id();
			$table->bigInteger('total_population_national');
            $table->bigInteger('total_population_regional');
            $table->bigInteger('total_population_provincial');
            $table->bigInteger('total_population_cities');
            $table->bigInteger('total_population_municipalities');

			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('PSGCs');
	}
};
