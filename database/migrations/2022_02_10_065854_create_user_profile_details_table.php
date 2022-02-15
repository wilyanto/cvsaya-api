<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfileDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cvsaya_employee_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->longText('about')->nullable();
            $table->bigInteger('position_id')->unsigned();
            $table->string('website_url')->nullable();
            $table->json('selfie_about')->nullable();
            $table->enum('religion',['Buddha','Islam','Kristen','Kong Hu Cu'])->nullable();
            $table->string('reference')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cvsaya_employee_details');
    }
}
