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
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->unsignedBigInteger('car_id')->nullable();
            $table->unsignedBigInteger('rent_id')->nullable();
            $table->bigInteger('tips')->nullable();
            $table->boolean('is_rent')->default(0);
            $table->unsignedBigInteger('customs_id');
            $table->unsignedBigInteger('client_id');
            $table->string('number');
            $table->enum('size', [20, 40, 'box']);
            $table->bigInteger('price')->nullable()->default(0);
            $table->bigInteger('rent_price')->nullable()->default(0);
            $table->enum('status', ['wait', 'transport', 'done', 'rent', 'storage'])->default('wait');
            $table->timestamp('transfer_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('date_empty')->nullable();
            $table->timestamps();

            $table->foreign('driver_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
            $table->foreign('rent_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('customs_id')->references('id')->on('customs_declarations')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');

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
