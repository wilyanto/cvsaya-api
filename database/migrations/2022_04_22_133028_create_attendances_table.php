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
        Schema::create('attendances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('attendance_type');
            $table->timestamp('attended_at');
            $table->timestamp('scheduled_at');
            $table->uuid('attendance_qr_code_id');
            $table->uuid('image');
            $table->decimal('longitude', 14, 6)->nullable();
            $table->decimal('latitude', 14, 6)->nullable();
            $table->string('ip');
            $table->timestamps();

            $table->foreign('attendance_qr_code_id')->references('id')->on('attendance_qr_codes');
            $table->foreign('image')->references('id')->on('documents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
};
