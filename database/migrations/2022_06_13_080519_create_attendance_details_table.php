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
        Schema::create('attendance_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('attendance_type');
            $table->timestamp('attended_at')->useCurrent()->nullable();
            $table->timestamp('scheduled_at')->useCurrent();
            $table->uuid('attendance_qr_code_id')->nullable();
            $table->string('image')->nullable();
            $table->point('location')->nullable();
            $table->string('ip');
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable();
            $table->timestamps();

            $table->foreign('verified_by')->references('id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_details');
    }
};
