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
        Schema::create('customs_declarations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('statement_number');
            $table->unsignedBigInteger('client_id');
            $table->string('importer_name')->nullable();
            $table->date('expire_customs')->nullable();
            $table->bigInteger('customs_weight')->default(0)->nullable();
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customs_declarations');
    }
};
