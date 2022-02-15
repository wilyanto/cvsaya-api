<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogUserProfileDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cvsaya_log_employee_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_detail_id')->unsigned();
            $table->bigInteger('position_id')->unsigned()->nullable();
            $table->longText('about')->nullable();
            $table->string('website_url')->nullable();
            $table->json('selfie_picture')->nullable();
            $table->enum('religion',['Buddha','Islam','Kristen','Kong Hu Cu'])->nullable();
            $table->string('reference')->nullable();
            $table->timestamp('created_at');
        });

        Schema::table('cvsaya_log_employee_details',function(Blueprint $table){
            $table->foreign('employee_detail_id')->references('id')->on('cvsaya_employee_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cvsaya_log_employee_details',function(Blueprint $table){
            // $table->dropForeign(['position_id']);
            $table->dropForeign(['employee_detail_id']);
        });

        Schema::dropIfExists('cvsaya_log_employee_details');
    }
}
