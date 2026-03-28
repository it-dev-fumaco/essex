<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $driver = DB::getDriverName();

        // Normalize legacy column names (MySQL/MariaDB) to snake_case and required schema.
        if ($driver === 'mysql') {
            // designation -> designation_name
            if (Schema::hasColumn('users', 'designation') && ! Schema::hasColumn('users', 'designation_name')) {
                DB::statement("ALTER TABLE `users` CHANGE COLUMN `designation` `designation_name` VARCHAR(255) NULL");
            }

            // position_applied_for -> position_applied_for1
            if (Schema::hasColumn('users', 'position_applied_for') && ! Schema::hasColumn('users', 'position_applied_for1')) {
                DB::statement("ALTER TABLE `users` CHANGE COLUMN `position_applied_for` `position_applied_for1` VARCHAR(255) NULL");
            }

            // phone_local_no -> telephone
            if (Schema::hasColumn('users', 'phone_local_no') && ! Schema::hasColumn('users', 'telephone')) {
                DB::statement("ALTER TABLE `users` CHANGE COLUMN `phone_local_no` `telephone` INT NULL");
            }

            // Barangay / City -> barangay / city
            if (Schema::hasColumn('users', 'Barangay') && ! Schema::hasColumn('users', 'barangay')) {
                DB::statement("ALTER TABLE `users` CHANGE COLUMN `Barangay` `barangay` VARCHAR(255) NULL");
            }
            if (Schema::hasColumn('users', 'City') && ! Schema::hasColumn('users', 'city')) {
                DB::statement("ALTER TABLE `users` CHANGE COLUMN `City` `city` VARCHAR(255) NULL");
            }
        }

        Schema::table('users', function (Blueprint $table) {
            // Core identifiers
            if (! Schema::hasColumn('users', 'user_id')) {
                $table->string('user_id')->nullable()->after('id');
            }
            if (! Schema::hasColumn('users', 'employee_id')) {
                $table->string('employee_id')->nullable()->after('user_id');
            }
            if (! Schema::hasColumn('users', 'department_id')) {
                $table->unsignedInteger('department_id')->nullable()->after('employee_id');
            }
            if (! Schema::hasColumn('users', 'shift_group_id')) {
                $table->unsignedInteger('shift_group_id')->nullable()->after('department_id');
            }

            // Auth / security
            if (! Schema::hasColumn('users', 'password')) {
                $table->string('password')->nullable()->after('shift_group_id');
            }
            if (! Schema::hasColumn('users', 'id_security_key')) {
                $table->string('id_security_key')->nullable()->after('password');
            }

            // Names
            if (! Schema::hasColumn('users', 'employee_name')) {
                $table->string('employee_name')->after('id_security_key');
            }
            if (! Schema::hasColumn('users', 'employee_last_name')) {
                $table->string('employee_last_name')->nullable()->after('employee_name');
            }
            if (! Schema::hasColumn('users', 'employee_first_name')) {
                $table->string('employee_first_name')->nullable()->after('employee_last_name');
            }
            if (! Schema::hasColumn('users', 'employee_middle_name')) {
                $table->string('employee_middle_name')->nullable()->after('employee_first_name');
            }
            if (! Schema::hasColumn('users', 'nick_name')) {
                $table->string('nick_name')->nullable()->after('employee_middle_name');
            }

            // Designation / org / contacts
            if (! Schema::hasColumn('users', 'designation_id')) {
                $table->unsignedInteger('designation_id')->nullable()->after('nick_name');
            }
            if (! Schema::hasColumn('users', 'designation_name')) {
                $table->string('designation_name')->nullable()->after('designation_id');
            }
            if (! Schema::hasColumn('users', 'branch')) {
                $table->unsignedInteger('branch')->nullable()->after('designation_name');
            }
            if (! Schema::hasColumn('users', 'telephone')) {
                $table->integer('telephone')->nullable()->after('branch');
            }

            // Email
            if (! Schema::hasColumn('users', 'email')) {
                $table->string('email')->after('telephone');
            }
            if (! Schema::hasColumn('users', 'personal_email')) {
                $table->string('personal_email')->nullable()->after('email');
            }

            // Enums (values based on existing code + provided requirements)
            if (! Schema::hasColumn('users', 'user_type')) {
                $table->enum('user_type', ['Employee', 'Applicant'])->nullable()->after('personal_email');
            }
            if (! Schema::hasColumn('users', 'employment_status')) {
                $table->enum('employment_status', ['Regular', 'Contractual', 'Probationary'])->nullable()->after('user_type');
            }
            if (! Schema::hasColumn('users', 'job_source')) {
                $table->enum('job_source', ['Online', 'Referral', 'Walk-in', 'Agency', 'Other'])->nullable()->after('employment_status');
            }
            if (! Schema::hasColumn('users', 'position_applied_for1')) {
                $table->string('position_applied_for1')->nullable()->after('job_source');
            }
            if (! Schema::hasColumn('users', 'position_applied_for2')) {
                $table->string('position_applied_for2')->nullable()->after('position_applied_for1');
            }

            // Address / gov IDs
            if (! Schema::hasColumn('users', 'address')) {
                $table->string('address')->nullable()->after('position_applied_for2');
            }
            if (! Schema::hasColumn('users', 'barangay')) {
                $table->string('barangay')->nullable()->after('address');
            }
            if (! Schema::hasColumn('users', 'city')) {
                $table->string('city')->nullable()->after('barangay');
            }
            if (! Schema::hasColumn('users', 'contact_no')) {
                $table->string('contact_no')->nullable()->after('city');
            }
            if (! Schema::hasColumn('users', 'sss_no')) {
                $table->string('sss_no')->nullable()->after('contact_no');
            }
            if (! Schema::hasColumn('users', 'tin_no')) {
                $table->string('tin_no')->nullable()->after('sss_no');
            }

            // More IDs / details
            if (! Schema::hasColumn('users', 'pagibig_no')) {
                $table->string('pagibig_no')->nullable()->after('tin_no');
            }
            if (! Schema::hasColumn('users', 'philhealth_no')) {
                $table->string('philhealth_no')->nullable()->after('pagibig_no');
            }

            // Verification / grouping / dates
            if (! Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('tin_no');
            }
            if (! Schema::hasColumn('users', 'user_group')) {
                $table->enum('user_group', ['Manager', 'Employee', 'HR Personnel'])->nullable()->after('email_verified_at');
            }
            if (! Schema::hasColumn('users', 'birth_date')) {
                $table->string('birth_date')->nullable()->after('user_group');
            }
            if (! Schema::hasColumn('users', 'date_joined')) {
                $table->date('date_joined')->nullable()->after('birth_date');
            }

            // Status fields (civil_status/status/applicant_status)
            if (! Schema::hasColumn('users', 'civil_status')) {
                $table->enum('civil_status', ['Single', 'Married', 'Separated', 'Widowed'])->nullable()->after('date_joined');
            }
            if (! Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['Active', 'Inactive'])->default('Active')->after('civil_status');
            }
            if (! Schema::hasColumn('users', 'resignation_date')) {
                $table->date('resignation_date')->nullable()->after('status');
            }
            if (! Schema::hasColumn('users', 'applicant_status')) {
                $table->enum('applicant_status', ['Submitted', 'For Review', 'Approved', 'Rejected'])->nullable()->default('Submitted')->after('resignation_date');
            }

            // Emergency / misc
            if (! Schema::hasColumn('users', 'source')) {
                $table->string('source')->nullable()->after('applicant_status');
            }
            if (! Schema::hasColumn('users', 'contact_person')) {
                $table->string('contact_person')->nullable()->after('source');
            }
            if (! Schema::hasColumn('users', 'contact_person_no')) {
                $table->string('contact_person_no')->nullable()->after('contact_person');
            }
            if (! Schema::hasColumn('users', 'image')) {
                $table->string('image')->nullable()->after('contact_person_no');
            }

            // Audit-ish fields
            if (! Schema::hasColumn('users', 'created_by')) {
                $table->string('created_by')->nullable()->after('deleted_at');
            }
            if (! Schema::hasColumn('users', 'last_modified_by')) {
                $table->string('last_modified_by')->nullable()->after('created_by');
            }

            // Additional profile fields
            if (! Schema::hasColumn('users', 'gender')) {
                $table->string('gender')->nullable()->after('last_modified_by');
            }
            if (! Schema::hasColumn('users', 'last_login_date')) {
                $table->dateTime('last_login_date')->nullable()->after('gender');
            }
            if (! Schema::hasColumn('users', 'last_active')) {
                $table->dateTime('last_active')->nullable()->after('last_login_date');
            }
            if (! Schema::hasColumn('users', 'reporting_to')) {
                $table->string('reporting_to')->nullable()->after('last_active');
            }

            if (! Schema::hasColumn('users', 'company')) {
                $table->string('company')->nullable()->default('FUMACO Inc.')->after('reporting_to');
            }
            if (! Schema::hasColumn('users', 'joining_date')) {
                $table->string('joining_date')->nullable()->after('company');
            }
            if (! Schema::hasColumn('users', 'payroll_type')) {
                $table->string('payroll_type')->nullable()->after('joining_date');
            }
            if (! Schema::hasColumn('users', 'work_setup')) {
                $table->enum('work_setup', ['Hybrid', 'On-Site', 'Remote'])->nullable()->after('payroll_type');
            }

            // Laravel standard columns (required by your target schema)
            if (! Schema::hasColumn('users', 'remember_token')) {
                $table->rememberToken();
            }
            if (! Schema::hasColumn('users', 'created_at') || ! Schema::hasColumn('users', 'updated_at')) {
                $table->timestamps();
            }
            if (! Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // Apply required NOT NULL + defaults + type updates using driver-specific SQL
        if ($driver === 'mysql') {
            // Ensure email required
            DB::statement("UPDATE `users` SET `email` = COALESCE(`email`, CONCAT('unknown+', `id`, '@invalid.local'))");
            DB::statement("ALTER TABLE `users` MODIFY COLUMN `email` VARCHAR(255) NOT NULL");

            // user_id should be VARCHAR NULL (legacy is INT NOT NULL)
            if (Schema::hasColumn('users', 'user_id')) {
                DB::statement("ALTER TABLE `users` MODIFY COLUMN `user_id` VARCHAR(255) NULL");
            }

            // employee_id should be VARCHAR NULL
            if (Schema::hasColumn('users', 'employee_id')) {
                DB::statement("ALTER TABLE `users` MODIFY COLUMN `employee_id` VARCHAR(255) NULL");
            }

            // department_id should be INT NULL (legacy is INT NOT NULL)
            if (Schema::hasColumn('users', 'department_id')) {
                DB::statement("ALTER TABLE `users` MODIFY COLUMN `department_id` INT NULL");
            }

            // password should be VARCHAR NULL
            if (Schema::hasColumn('users', 'password')) {
                DB::statement("ALTER TABLE `users` MODIFY COLUMN `password` VARCHAR(255) NULL");
            }

            // employee_name required
            DB::statement("ALTER TABLE `users` MODIFY COLUMN `employee_name` VARCHAR(255) NOT NULL");

            // civil_status required (backfill then enforce)
            if (Schema::hasColumn('users', 'civil_status')) {
                DB::statement("UPDATE `users` SET `civil_status` = COALESCE(`civil_status`, 'Single')");
                DB::statement("ALTER TABLE `users` MODIFY COLUMN `civil_status` ENUM('Single','Married','Separated','Widowed') NOT NULL");
            }

            // status required w/ default Active (fix legacy malformed enum if any)
            if (Schema::hasColumn('users', 'status')) {
                DB::statement("UPDATE `users` SET `status` = COALESCE(`status`, 'Active')");
                DB::statement("ALTER TABLE `users` MODIFY COLUMN `status` ENUM('Active','Inactive') NOT NULL DEFAULT 'Active'");
            }

            // applicant_status default Submitted (and nullable per requirement)
            if (Schema::hasColumn('users', 'applicant_status')) {
                DB::statement("UPDATE `users` SET `applicant_status` = COALESCE(`applicant_status`, 'Submitted')");
                DB::statement("ALTER TABLE `users` MODIFY COLUMN `applicant_status` ENUM('Submitted','For Review','Approved','Rejected') NULL DEFAULT 'Submitted'");
            }

            // company required default 'FUMACO Inc.'
            if (Schema::hasColumn('users', 'company')) {
                DB::statement("UPDATE `users` SET `company` = COALESCE(`company`, 'FUMACO Inc.')");
                DB::statement("ALTER TABLE `users` MODIFY COLUMN `company` VARCHAR(255) NOT NULL DEFAULT 'FUMACO Inc.'");
            }
        }

        // Indexes (scalable lookups)
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'email')) {
                $table->index('email', 'users_email_idx');
            }
            if (Schema::hasColumn('users', 'employee_id')) {
                $table->index('employee_id', 'users_employee_id_idx');
            }
            if (Schema::hasColumn('users', 'department_id')) {
                $table->index('department_id', 'users_department_id_idx');
            }
            if (Schema::hasColumn('users', 'shift_group_id')) {
                $table->index('shift_group_id', 'users_shift_group_id_idx');
            }
        });
    }

    public function down(): void
    {
        // Best-effort rollback: remove added columns/indexes (legacy column renames are not reversed).
        Schema::table('users', function (Blueprint $table) {
            $indexes = [
                'users_email_idx',
                'users_employee_id_idx',
                'users_department_id_idx',
                'users_shift_group_id_idx',
            ];
            foreach ($indexes as $idx) {
                try {
                    $table->dropIndex($idx);
                } catch (\Throwable $e) {
                    // ignore
                }
            }

            $drop = [
                'employee_id',
                'shift_group_id',
                'id_security_key',
                'employee_last_name',
                'employee_first_name',
                'employee_middle_name',
                'nick_name',
                'designation_id',
                'designation_name',
                'branch',
                'telephone',
                'personal_email',
                'job_source',
                'position_applied_for1',
                'position_applied_for2',
                'address',
                'barangay',
                'city',
                'contact_no',
                'sss_no',
                'tin_no',
                'pagibig_no',
                'philhealth_no',
                'date_joined',
                'civil_status',
                'resignation_date',
                'applicant_status',
                'source',
                'contact_person',
                'contact_person_no',
                'image',
                'created_by',
                'last_modified_by',
                'gender',
                'last_login_date',
                'last_active',
                'reporting_to',
                'company',
                'joining_date',
                'payroll_type',
                'work_setup',
            ];

            $existing = array_values(array_filter($drop, fn ($c) => Schema::hasColumn('users', $c)));
            if ($existing !== []) {
                $table->dropColumn($existing);
            }
        });
    }
};

