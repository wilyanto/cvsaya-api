<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cv_certifications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('name');
            $table->string('organization');
            $table->date('issued_at');
            $table->date('expired_at')->nullable();
            $table->string('credential_id')->nullable();
            $table->longText('credential_url')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cv_log_certifications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('certification_id')->unsigned();
            $table->string('name')->nullable();
            $table->string('organization')->nullable();
            $table->date('issued_at')->nullable();
            $table->date('expired_at')->nullable();
            $table->string('credential_id')->nullable();
            $table->longText('credential_url')->nullable();
            $table->timestamp('created_at');
        });

        Schema::table('cv_log_certifications',function(Blueprint $table){
            $table->foreign('certification_id')->references('id')->on('cv_certifications');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cv_log_certifications',function(Blueprint $table){
            $table->dropForeign(['certification_id']);
        });

        Schema::dropIfExists('cv_log_certifications');

        Schema::dropIfExists('cv_certifications');
    }
}
