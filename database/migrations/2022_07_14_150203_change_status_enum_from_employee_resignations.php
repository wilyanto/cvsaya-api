<?php

use App\Models\EmployeeResignation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::table('employee_resignations', function (Blueprint $table) {
            DB::statement("ALTER TABLE employee_resignations MODIFY COLUMN status ENUM('declined', 'accepted', 'pending', 'cancelled', 'acknowledged')");
            EmployeeResignation::where('status', 'declined')->update(['status' => 'cancelled']);
            EmployeeResignation::where('status', 'accepted')->update(['status' => 'acknowledged']);
            DB::statement("ALTER TABLE employee_resignations MODIFY COLUMN status ENUM('pending', 'cancelled', 'acknowledged')");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_resignations', function (Blueprint $table) {
            DB::statement("ALTER TABLE employee_resignations MODIFY COLUMN status ENUM('declined', 'accepted', 'pending', 'cancelled', 'acknowledged')");
            EmployeeResignation::where('status', 'cancelled')->update(['status' => 'declined']);
            EmployeeResignation::where('status', 'acknowledged')->update(['status' => 'accepted']);
            DB::statement("ALTER TABLE employee_resignations MODIFY COLUMN status ENUM('declined', 'accepted', 'pending')");
        });
    }
};
