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
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['employment_type_id']);
            $table->dropColumn('employment_type_id');
            $table->string('type')->default('daily')->after('position_id');
            $table->boolean('is_default')->default(true)->after('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->bigInteger('employment_type_id')->unsigned()->after('position_id')->nullable();
            $table->foreign('employment_type_id')->references('id')->on('employment_types');
            $table->dropColumn('type');
            $table->dropColumn('is_default');
        });
    }
};
