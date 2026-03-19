<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            if (Schema::hasColumn('users', 'Barangay') && ! Schema::hasColumn('users', 'barangay')) {
                DB::statement("ALTER TABLE `users` CHANGE COLUMN `Barangay` `barangay` VARCHAR(255) NULL");
            }
            if (Schema::hasColumn('users', 'City') && ! Schema::hasColumn('users', 'city')) {
                DB::statement("ALTER TABLE `users` CHANGE COLUMN `City` `city` VARCHAR(255) NULL");
            }
        }

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'work_setup')) {
                $table->enum('work_setup', ['Hybrid', 'On-Site', 'Remote'])->nullable()->after('payroll_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'work_setup')) {
                $table->dropColumn('work_setup');
            }
        });

        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            if (Schema::hasColumn('users', 'barangay') && ! Schema::hasColumn('users', 'Barangay')) {
                DB::statement("ALTER TABLE `users` CHANGE COLUMN `barangay` `Barangay` VARCHAR(255) NULL");
            }
            if (Schema::hasColumn('users', 'city') && ! Schema::hasColumn('users', 'City')) {
                DB::statement("ALTER TABLE `users` CHANGE COLUMN `city` `City` VARCHAR(255) NULL");
            }
        }
    }
};

