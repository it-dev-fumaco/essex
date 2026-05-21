<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('users') || ! Schema::hasColumn('users', 'image')) {
            return;
        }

        $driver = DB::getDriverName();

        // Avoid doctrine/dbal dependency by using driver SQL.
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE `users` MODIFY COLUMN `image` TEXT NULL");
        } elseif ($driver === 'sqlsrv') {
            DB::statement("ALTER TABLE [users] ALTER COLUMN [image] NVARCHAR(MAX) NULL");
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('users') || ! Schema::hasColumn('users', 'image')) {
            return;
        }

        $driver = DB::getDriverName();

        // Best-effort rollback to a reasonable VARCHAR length.
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE `users` MODIFY COLUMN `image` VARCHAR(500) NULL");
        } elseif ($driver === 'sqlsrv') {
            DB::statement("ALTER TABLE [users] ALTER COLUMN [image] NVARCHAR(500) NULL");
        }
    }
};

