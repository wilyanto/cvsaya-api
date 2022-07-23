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
        Schema::create('employee_payslips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees');
            $table->foreignId('payroll_period_id')->constrained('payroll_periods');
            $table->enum('status', ['unpaid', 'paid']);
            $table->timestamp('generated_at')->nullable()->default(null);
            $table->foreignId('generated_by')->nullable()->default(null)->constrained('employees');
            $table->timestamp('paid_at')->nullable()->default(null);
            $table->foreignId('paid_by')->nullable()->default(null)->constrained('employees');
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
        Schema::dropIfExists('employee_payslips');
    }
};
