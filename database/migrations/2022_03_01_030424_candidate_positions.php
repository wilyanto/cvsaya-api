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
        Schema::create('candidate_positions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamp('validated_at')->default(null)->nullable();
            $table->bigInteger('inserted_by')->unsigned();
            $table->timestamps();
        });

        Schema::table('cv_expected_positions',function(Blueprint $table){
            $table->foreign('expected_position')->references('id')->on('candidate_positions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cv_expected_positions', function (Blueprint $table) {
            $table->dropForeign(['expected_position']);
        });


        Schema::dropIfExists('candidate_positions');
    }
};
