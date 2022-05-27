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
        Schema::create('leave_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->timestamp('started_at');
            $table->timestamp('ended_at');
            $table->unsignedBigInteger('occasion_id');
            $table->string('reason');
            $table->string('status');
            $table->timestamp('answered_at');
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('occasion_id')->references('id')->on('leave_permission_occasions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leave_permissions');
    }
};
