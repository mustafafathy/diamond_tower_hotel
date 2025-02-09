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
        Schema::table('reservations', function (Blueprint $table) {
            $table->json('persons')->after('number_of_guests');
            $table->float('gov_tax')->after('tax');
            $table->string('first_name')->after('coupon_id');
            $table->string('last_name')->after('first_name');
            $table->boolean('guest_name')->after('last_name')->nullable();
            $table->string('email')->after('guest_name');
            $table->string('country')->after('email');
            $table->string('phone')->after('country');
            $table->boolean('same_person')->after('phone');
            $table->boolean('for_work')->after('same_person');
            $table->boolean('need_parking')->after('for_work');
            $table->text('notes')->after('need_parking')->nullable();
            $table->string('arrival_time')->after('notes');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            //
        });
    }
};
