<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('early_clock_out_attendances', function (Blueprint $table) {
            $table->id();
            $table->uuid('attendance_detail_id');
            $table->string('note');
            $table->enum('status', ['pending', 'accepted', 'rejected']);
            $table->timestamps();

            $table->foreign('attendance_detail_id')->references('id')->on('attendance_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('early_clock_out_attendances');
    }
};
