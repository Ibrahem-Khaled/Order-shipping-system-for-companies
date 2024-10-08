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
        Schema::create('containers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('driver_id')->nullable();
            $table->bigInteger('car_id')->nullable();
            $table->bigInteger('rent_id')->nullable();
            $table->bigInteger('tips')->nullable();
            $table->boolean('is_rent')->default(0);
            $table->bigInteger('customs_id');
            $table->bigInteger('client_id');
            $table->string('number');
            $table->enum('size', [20, 40, 'box']);
            $table->bigInteger('price')->nullable()->default(0);
            $table->bigInteger('rent_price')->nullable()->default(0);
            $table->enum('status', ['wait', 'transport', 'done', 'rent', 'storage'])->default('wait');
            $table->timestamp('transfer_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('date_empty')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('containers');
    }
};
