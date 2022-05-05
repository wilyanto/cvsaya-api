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
        Schema::create('attendance_qr_codes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('location_name');
            $table->decimal('longitude', 14, 6)->nullable();
            $table->decimal('latitude', 14, 6)->nullable();
            $table->unsignedInteger('radius')->nullable()->comment('in meter');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_qr_codes');
    }
};
