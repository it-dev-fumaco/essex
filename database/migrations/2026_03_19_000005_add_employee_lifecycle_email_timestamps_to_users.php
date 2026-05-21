<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'welcome_email_sent_at')) {
                $table->timestamp('welcome_email_sent_at')->nullable()->after('joining_date');
            }
            if (! Schema::hasColumn('users', 'onboarding_email_sent_at')) {
                $table->timestamp('onboarding_email_sent_at')->nullable()->after('welcome_email_sent_at');
            }
            if (! Schema::hasColumn('users', 'offboarding_email_sent_at')) {
                $table->timestamp('offboarding_email_sent_at')->nullable()->after('onboarding_email_sent_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $cols = [];
            foreach (['welcome_email_sent_at', 'onboarding_email_sent_at', 'offboarding_email_sent_at'] as $c) {
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

