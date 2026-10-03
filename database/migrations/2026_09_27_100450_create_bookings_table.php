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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // Customer who makes the booking
            $table->foreignId('customer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Service being booked
            $table->foreignId('service_id')
                ->constrained()
                ->cascadeOnDelete();

            // Booking date and time
            $table->date('booking_date');
            $table->time('booking_time');

            // Booking status
            $table->enum('status', [
                'pending',
                'confirmed',
                'completed',
                'cancelled',
                'rejected'
            ])->default('pending');

            // Optional note from customer
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
