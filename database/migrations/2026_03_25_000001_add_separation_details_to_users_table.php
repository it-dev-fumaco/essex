<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'separation_date')) {
                $after = Schema::hasColumn('users', 'resignation_date') ? 'resignation_date' : 'status';
                $table->date('separation_date')->nullable()->after($after);
            }
            if (! Schema::hasColumn('users', 'separation_type')) {
                $table->string('separation_type', 50)->nullable()->after('separation_date');
            }
            if (! Schema::hasColumn('users', 'separation_reason')) {
                $table->text('separation_reason')->nullable()->after('separation_type');
            }
            if (! Schema::hasColumn('users', 'clearance_status')) {
                $table->string('clearance_status', 20)->nullable()->after('separation_reason');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $cols = [];
            foreach (['separation_date', 'separation_type', 'separation_reason', 'clearance_status'] as $c) {
                if (Schema::hasColumn('users', $c)) {
                    $cols[] = $c;
                }
            }
            if ($cols !== []) {
                $table->dropColumn($cols);
            }
        });
    }
};
