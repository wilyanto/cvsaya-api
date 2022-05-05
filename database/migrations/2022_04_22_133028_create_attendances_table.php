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
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('attendance_type');
            $table->timestamp('attended_at');
            $table->timestamp('scheduled_at');
            $table->uuid('attendance_qr_code_id');
            $table->decimal('longitude', 14, 6)->nullable();
            $table->decimal('latitude', 14, 6)->nullable();
            $table->string('ip');
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('attendance_qr_code_id')->references('id')->on('attendance_qr_codes');
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
