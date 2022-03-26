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
        Schema::table('cv_addresses',function(Blueprint $table){
            $table->renameColumn('detail','address');
        });

        Schema::rename('cv_addresses', 'cv_domiciles');

        Schema::table('cv_log_addresses',function(Blueprint $table){
            $table->dropForeign(['cv_address_id']);
            $table->renameColumn('detail','address');
            $table->renameColumn('cv_address_id','cv_domicile_id');
        });

        Schema::rename('cv_log_addresses', 'cv_log_domiciles');

        Schema::table('cv_log_domiciles',function(Blueprint $table){
            $table->foreign('cv_domicile_id')->references('id')->on('cv_domiciles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('cv_domiciles','cv_addresses');

        Schema::table('cv_addresses',function(Blueprint $table){
            $table->renameColumn('address','detail');
        });

        Schema::table('cv_log_domiciles',function(Blueprint $table){
            $table->dropForeign(['cv_domicile_id']);
            $table->renameColumn('address','detail');
            $table->renameColumn('cv_domicile_id','cv_address_id');
        });

        Schema::rename( 'cv_log_domiciles','cv_log_addresses');

        Schema::table('cv_log_addresses',function(Blueprint $table){
            $table->foreign('cv_address_id')->references('id')->on('cv_addresses');
        });
    }
};
