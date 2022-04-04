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
        Schema::create('documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->longText('file_name');
            $table->string('mime_type');
            $table->bigInteger('type_id')->unsigned();
            $table->longText('original_file_name');
            $table->timestamps();
        });

        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('cv_documents', function (Blueprint $table) {
            $table->dropColumn('right_selfie');
            $table->dropColumn('left_selfie');
            $table->dropColumn('front_selfie');
            $table->dropColumn('identity_card');
        });

        Schema::table('cv_documents', function (Blueprint $table) {
            $table->uuid('right_selfie')->nullable()->after('user_id');
            $table->uuid('left_selfie')->nullable()->after('user_id');
            $table->uuid('front_selfie')->nullable()->after('user_id');
            $table->uuid('identity_card')->nullable()->after('user_id');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->foreign('type_id')->references('id')->on('document_types');
        });

        Schema::table('cv_documents', function (Blueprint $table) {
            $table->foreign('right_selfie')->references('id')->on('documents');
            $table->foreign('left_selfie')->references('id')->on('documents');
            $table->foreign('front_selfie')->references('id')->on('documents');
            $table->foreign('identity_card')->references('id')->on('documents');
        });

        Schema::table('cv_log_documents', function (Blueprint $table) {
            $table->dropColumn('right_selfie');
            $table->dropColumn('left_selfie');
            $table->dropColumn('front_selfie');
            $table->dropColumn('identity_card');
        });

        Schema::table('cv_log_documents', function (Blueprint $table) {
            $table->uuid('right_selfie')->nullable()->after('document_id');
            $table->uuid('left_selfie')->nullable()->after('document_id');
            $table->uuid('front_selfie')->nullable()->after('document_id');
            $table->uuid('identity_card')->nullable()->after('document_id');
        });

        Schema::table('cv_log_documents', function (Blueprint $table) {
            $table->foreign('right_selfie')->references('id')->on('documents');
            $table->foreign('left_selfie')->references('id')->on('documents');
            $table->foreign('front_selfie')->references('id')->on('documents');
            $table->foreign('identity_card')->references('id')->on('documents');
        });

        Schema::table('cv_experiences', function (Blueprint $table) {
            $table->dropColumn('payslip_img');
        });

        Schema::table('cv_log_experiences', function (Blueprint $table) {
            $table->dropColumn('payslip_img');
        });

        DB::statement("ALTER TABLE cv_experiences MODIFY jobdesc longText AFTER media");
        DB::statement("ALTER TABLE cv_experiences MODIFY previous_salary int(11) AFTER media");
        DB::statement("ALTER TABLE cv_experiences MODIFY reference varchar(255) AFTER media");
        DB::statement("ALTER TABLE cv_experiences MODIFY resign_reason longText AFTER media");
        DB::statement("ALTER TABLE cv_experiences MODIFY position_id bigint(20) UNSIGNED AFTER media");
        DB::statement("ALTER TABLE cv_experiences MODIFY employment_type_id bigint(20) UNSIGNED AFTER media");

        DB::statement("ALTER TABLE cv_log_experiences MODIFY jobdesc longText AFTER media");
        DB::statement("ALTER TABLE cv_log_experiences MODIFY previous_salary int(11) AFTER media");
        DB::statement("ALTER TABLE cv_log_experiences MODIFY reference varchar(255) AFTER media");
        DB::statement("ALTER TABLE cv_log_experiences MODIFY resign_reason longText AFTER media");
        DB::statement("ALTER TABLE cv_log_experiences MODIFY position_id bigint(20) UNSIGNED AFTER media");
        DB::statement("ALTER TABLE cv_log_experiences MODIFY employment_type_id bigint(20) UNSIGNED AFTER media");

        Schema::table('cv_experiences', function (Blueprint $table) {
            $table->uuid('payslip')->nullable()->after('position_id');
        });

        Schema::table('cv_log_experiences', function (Blueprint $table) {
            $table->uuid('payslip')->nullable()->after('position_id');
        });

        Schema::table('cv_experiences', function (Blueprint $table) {
            $table->foreign('payslip')->references('id')->on('documents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cv_experiences', function (Blueprint $table) {
            $table->dropForeign(['payslip']);
        });

        Schema::table('cv_experiences', function (Blueprint $table) {
            $table->dropColumn('payslip');
        });

        Schema::table('cv_log_experiences', function (Blueprint $table) {
            $table->dropColumn('payslip');
        });

        DB::statement("ALTER TABLE cv_experiences MODIFY jobdesc longText AFTER deleted_at");
        DB::statement("ALTER TABLE cv_experiences MODIFY jobdesc longText AFTER deleted_at");
        DB::statement("ALTER TABLE cv_experiences MODIFY previous_salary int(11) AFTER deleted_at");
        DB::statement("ALTER TABLE cv_experiences MODIFY reference varchar(255) AFTER deleted_at");
        DB::statement("ALTER TABLE cv_experiences MODIFY resign_reason longText AFTER deleted_at");
        DB::statement("ALTER TABLE cv_experiences MODIFY position_id bigint(20) UNSIGNED AFTER deleted_at");
        DB::statement("ALTER TABLE cv_experiences MODIFY employment_type_id bigint(20) UNSIGNED AFTER deleted_at");

        DB::statement("ALTER TABLE cv_experiences MODIFY jobdesc longText AFTER deleted_at");
        DB::statement("ALTER TABLE cv_experiences MODIFY jobdesc longText AFTER deleted_at");
        DB::statement("ALTER TABLE cv_experiences MODIFY previous_salary int(11) AFTER deleted_at");
        DB::statement("ALTER TABLE cv_experiences MODIFY reference varchar(255) AFTER deleted_at");
        DB::statement("ALTER TABLE cv_experiences MODIFY resign_reason longText AFTER deleted_at");
        DB::statement("ALTER TABLE cv_experiences MODIFY position_id bigint(20) UNSIGNED AFTER deleted_at");
        DB::statement("ALTER TABLE cv_experiences MODIFY employment_type_id bigint(20) UNSIGNED AFTER deleted_at");

        Schema::table('cv_log_experiences', function (Blueprint $table) {
            $table->longtext('payslip_img')->nullable()->after('position_id');
        });

        Schema::table('cv_experiences', function (Blueprint $table) {
            $table->longtext('payslip_img')->nullable()->after('position_id');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['type_id']);
        });

        Schema::table('cv_documents', function (Blueprint $table) {
            $table->dropForeign(['right_selfie']);
            $table->dropForeign(['left_selfie']);
            $table->dropForeign(['front_selfie']);
            $table->dropForeign(['identity_card']);
        });

        Schema::table('cv_documents', function (Blueprint $table) {
            $table->dropColumn('right_selfie');
            $table->dropColumn('left_selfie');
            $table->dropColumn('front_selfie');
            $table->dropColumn('identity_card');
        });

        Schema::table('cv_documents', function (Blueprint $table) {
            $table->longtext('right_selfie')->nullable()->after('user_id');
            $table->longtext('left_selfie')->nullable()->after('user_id');
            $table->longtext('front_selfie')->nullable()->after('user_id');
            $table->longtext('identity_card')->nullable()->after('user_id');
        });

        Schema::table('cv_log_documents', function (Blueprint $table) {
            $table->dropForeign(['right_selfie']);
            $table->dropForeign(['left_selfie']);
            $table->dropForeign(['front_selfie']);
            $table->dropForeign(['identity_card']);
        });

        Schema::table('cv_log_documents', function (Blueprint $table) {
            $table->dropColumn('right_selfie');
            $table->dropColumn('left_selfie');
            $table->dropColumn('front_selfie');
            $table->dropColumn('identity_card');
        });

        Schema::table('cv_log_documents', function (Blueprint $table) {
            $table->longtext('right_selfie')->nullable()->after('document_id');
            $table->longtext('left_selfie')->nullable()->after('document_id');
            $table->longtext('front_selfie')->nullable()->after('document_id');
            $table->longtext('identity_card')->nullable()->after('document_id');
        });

        Schema::drop('document_types');

        Schema::drop('documents');
    }
};
