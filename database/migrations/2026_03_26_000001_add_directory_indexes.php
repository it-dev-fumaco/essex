<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Filters used by /services/directory
            $table->index(['user_type', 'status', 'employment_status'], 'users_directory_filter_idx');

            // Prefix search can use these indexes when querying with "term%"
            $table->index('employee_name', 'users_employee_name_idx');
            $table->index('nick_name', 'users_nick_name_idx');
            $table->index('email', 'users_email_idx');
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->index('order_no', 'departments_order_no_idx');
            $table->index('department', 'departments_department_idx');
        });

        Schema::table('designation', function (Blueprint $table) {
            $table->index('designation', 'designation_name_idx');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_directory_filter_idx');
            $table->dropIndex('users_employee_name_idx');
            $table->dropIndex('users_nick_name_idx');
            $table->dropIndex('users_email_idx');
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->dropIndex('departments_order_no_idx');
            $table->dropIndex('departments_department_idx');
        });

        Schema::table('designation', function (Blueprint $table) {
            $table->dropIndex('designation_name_idx');
        });
    }
};

