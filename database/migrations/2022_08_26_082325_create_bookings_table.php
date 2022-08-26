<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('duration');
            $table->string('car_no');
            $table->enum('duration_type', ['week', 'day']);
            $table->text('note')->nullable()->default(null);
            $table->boolean('has_paid')->default(false);
            $table->integer('total')->default(0);
            $table->integer('user_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
