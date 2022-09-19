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
        Schema::create('announcement_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('announcement_id')->constrained('announcements');
            $table->foreignId('employee_id')->constrained('employees');
            $table->string('note')->nullable();
            $table->timestamp('replied_at')->nullable()->default(null);
            $table->enum('status', ['unread', 'read']);
            $table->timestamp('seen_at')->nullable()->default(null);
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
        Schema::dropIfExists('announcement_employees');
    }
};
