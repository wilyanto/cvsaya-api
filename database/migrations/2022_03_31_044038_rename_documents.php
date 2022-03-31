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
        Schema::table('cv_documentations',function(Blueprint $table){
            $table->renameColumn('identity_picture_card','identity_card');
            $table->renameColumn('selfie_front','front_selfie');
            $table->renameColumn('selfie_left','left_selfie');
            $table->renameColumn('selfie_right','right_selfie');
        });

        Schema::rename('cv_documentations','cv_documents');

        Schema::table('cv_log_documentations',function(Blueprint $table){
            $table->renameColumn('identity_picture_card','identity_card');
            $table->renameColumn('selfie_front','front_selfie');
            $table->renameColumn('selfie_left','left_selfie');
            $table->renameColumn('selfie_right','right_selfie');
        });

        Schema::rename('cv_log_documentations','cv_log_documents');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('cv_documents','cv_documentations');

        Schema::table('cv_documentations',function(Blueprint $table){
            $table->renameColumn('identity_card','identity_picture_card');
            $table->renameColumn('front_selfie','selfie_front');
            $table->renameColumn('left_selfie','selfie_left',);
            $table->renameColumn('right_selfie','selfie_right');
        });

        Schema::rename('cv_log_documents','cv_log_documentations');

        Schema::table('cv_log_documentations',function(Blueprint $table){
            $table->renameColumn('identity_card','identity_picture_card');
            $table->renameColumn('front_selfie','selfie_front');
            $table->renameColumn('left_selfie','selfie_left');
            $table->renameColumn('right_selfie','selfie_right');
        });

    }
};
