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
        Schema::create('leave_permission_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('leave_permission_id');
            $table->uuid('document_id');
            $table->timestamps();

            $table->foreign('leave_permission_id')->references('id')->on('leave_permissions');
            $table->foreign('document_id')->references('id')->on('documents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leave_permission_documents');
    }
};
