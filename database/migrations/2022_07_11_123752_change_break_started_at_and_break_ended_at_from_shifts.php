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
        Schema::table('shifts', function (Blueprint $table) {
            $table->renameColumn('break_started_at', 'start_break');
            $table->renameColumn('break_ended_at', 'end_break');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shifts', function (Blueprint $table) {
            $table->renameColumn('start_break', 'break_started_at');
            $table->renameColumn('end_break', 'break_ended_at');
        });
    }
};
