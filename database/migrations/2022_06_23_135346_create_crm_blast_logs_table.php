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
        Schema::create('crm_blast_logs', function (Blueprint $table) {
            $table->id();
            $table->string('blast_loggable_type');
            $table->unsignedBigInteger('blast_loggable_id');
            $table->unsignedBigInteger('credential_id');
            $table->string('recipient_country_code');
            $table->string('recipient_phone_number');
            $table->unsignedBigInteger('blast_type_id');
            $table->json('message_param_value');
            $table->json('message_template');
            $table->timestamps();

            $table->foreign('blast_type_id')->references('id')->on('blast_types');
            $table->foreign('credential_id')->references('id')->on('crm_credentials');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_blast_logs');
    }
};
