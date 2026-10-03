<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // MySQL uses ENUM for the booking status.
        // SQLite tests do not need this alteration because SQLite
        // does not enforce ENUM values in the same way.
        if (DB::getDriverName() === 'mysql') {
            DB::statement("
                ALTER TABLE bookings
                MODIFY COLUMN status ENUM(
                    'pending',
                    'confirmed',
                    'completed',
                    'cancelled',
                    'rejected'
                ) NOT NULL DEFAULT 'pending'
            ");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("
                ALTER TABLE bookings
                MODIFY COLUMN status ENUM(
                    'pending',
                    'confirmed',
                    'completed',
                    'cancelled'
                ) NOT NULL DEFAULT 'pending'
            ");
        }
    }
};