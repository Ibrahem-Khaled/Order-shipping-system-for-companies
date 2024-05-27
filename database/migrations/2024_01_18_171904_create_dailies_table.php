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
        Schema::create('dailies', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('car_id')->nullable();
            $table->bigInteger('client_id')->nullable();
            $table->bigInteger('partner_id')->nullable();
            $table->bigInteger('employee_id')->nullable();
            $table->bigInteger('container_id')->nullable();
            $table->enum('type', ['deposit', 'withdraw', 'partner_withdraw'])->nullable();
            $table->float('price')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dailies');
    }
};
