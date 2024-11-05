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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade'); // Foreign key to the 'rooms' table
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key to the 'users' table
            $table->date('start_date'); // Reservation start date
            $table->date('end_date'); // Reservation end date
            $table->integer('total_price'); // Total price of the reservation
            $table->integer('number_of_guests'); // Number of guests for the reservation
            $table->boolean('is_confirmed')->default(false); // Reservation confirmation status
            $table->boolean('is_cancelled')->default(false); // Cancellation status
            $table->foreignId('coupon_id')->nullable()->constrained()->onDelete('set null'); // Optional coupon ID

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
