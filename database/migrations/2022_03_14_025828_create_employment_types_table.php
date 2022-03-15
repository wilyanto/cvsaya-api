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
        Schema::create('employment_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('cv_experiences',function (Blueprint $table){
            $table->dropColumn('employment_type');
            $table->bigInteger('employment_type_id')->unsigned()->nullable();
        });

        Schema::table('cv_experiences',function (Blueprint $table){
            $table->foreign('employment_type_id')->references('id')->on('employment_types');
        });

        Schema::table('cv_log_experiences',function (Blueprint $table){
            $table->dropColumn('employment_type');
            $table->bigInteger('employment_type_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cv_log_experiences',function (Blueprint $table){
            $table->string('employment_type')->nullable();
            $table->dropColumn('employment_type_id');
        });

        Schema::table('cv_experiences',function (Blueprint $table){
            $table->dropForeign(['employment_type_id']);
        });

        Schema::table('cv_experiences',function (Blueprint $table){
            $table->string('employment_type')->nullable();
            $table->dropColumn('employment_type_id');
        });

        Schema::dropIfExists('employment_types');
    }
};
