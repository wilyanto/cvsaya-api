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
        Schema::create('crm_credential_blast_type', function (Blueprint $table) {
            $table->foreignId('crm_credential_id')->constrained('crm_credentials')->nullable();
            $table->foreignId('blast_type_id')->constrained('blast_types')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_credential_blast_type');
    }
};
