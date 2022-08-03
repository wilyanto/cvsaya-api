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
        Schema::create('employee_payslip_ad_hocs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_salary_type_id')->constrained('company_salary_types');
            $table->foreignId('employee_payslip_id')->constrained('employee_payslips')->nullable();
            $table->string('name');
            $table->date('date');
            $table->decimal('amount', 10, 2);
            $table->string('note')->nullable();
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
        Schema::dropIfExists('employee_payslip_ad_hocs');
    }
};
