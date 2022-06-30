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
        Schema::create('crm_credential_quota_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credential_id')->constrained('crm_credentials')->nullable();
            $table->foreignId('quota_type_id')->constrained('quota_types')->nullable();
            $table->integer('remaining')->nullable();
            $table->integer('quota')->nullable();
            $table->timestamp('last_updated_at');
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
        Schema::dropIfExists('crm_credential_quota_types');
    }
};
