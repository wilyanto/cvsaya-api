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
        Schema::table('attendances', function (Blueprint $table) {
            $table->timestamp('verified_at')->after('ip')->nullable();
            $table->foreignId('verified_by')->after('verified_at')->nullable();
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
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('verified_at');
            $table->dropForeign(['verified_by']);

            $table->dropColumn('verified_by');
        });
    }
};
