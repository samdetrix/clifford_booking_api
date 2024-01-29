<?php

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

        Schema::create('accommodations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('standard_rack_rate', 8, 2);
            $table->enum('status', ['available', 'booked', 'maintenance'])->default('available');
            $table->integer('capacity')->unsigned()->default(1);
            $table->boolean('is_wifi_available')->default(true);
            $table->boolean('is_parking_available')->default(true);
            $table->text('amenities')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
        });
        
    }
    public function down(): void
    {
        Schema::dropIfExists('accommodations');
    }
};
