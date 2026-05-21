<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql' || ! Schema::hasTable('users') || ! Schema::hasColumn('users', 'user_group')) {
            return;
        }

        DB::statement("ALTER TABLE `users` MODIFY COLUMN `user_group` ENUM('Manager','Employee','HR Personnel','Editor') NULL");
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql' || ! Schema::hasTable('users') || ! Schema::hasColumn('users', 'user_group')) {
            return;
        }

        DB::statement("UPDATE `users` SET `user_group` = 'Employee' WHERE `user_group` = 'Editor'");
        DB::statement("ALTER TABLE `users` MODIFY COLUMN `user_group` ENUM('Manager','Employee','HR Personnel') NULL");
    }
};
