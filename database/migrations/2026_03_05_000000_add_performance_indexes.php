<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addColumnIndex('users', 'department_id');
        $this->addColumnIndex('users', 'designation_id');
        $this->addColumnIndex('users', 'user_type');
        $this->addColumnIndex('users', 'status');
        $this->addColumnIndex('users', ['user_type', 'status']);
        $this->addColumnIndex('users', 'source');

        $this->addColumnIndex('gatepass', 'user_id');
        $this->addColumnIndex('gatepass', 'status');
        $this->addColumnIndex('gatepass', 'date_filed');
        $this->addColumnIndex('gatepass', 'item_type');
        $this->addColumnIndex('gatepass', 'item_status');

        $this->addColumnIndex('notice_slip', 'user_id');
        $this->addColumnIndex('notice_slip', 'status');
        $this->addColumnIndex('notice_slip', 'leave_type_id');
        $this->addColumnIndex('notice_slip', 'date_from');
        $this->addColumnIndex('notice_slip', 'date_to');

        $this->addColumnIndex('examinee_answers', 'examinee_id');
        $this->addColumnIndex('examinee_answers', 'exam_id');
        $this->addColumnIndex('examinee_answers', 'exam_type_id');
        $this->addColumnIndex('examinee_answers', 'isCorrect');

        $this->addColumnIndex('biometrics', 'employee_id');
        $this->addColumnIndex('biometrics', 'bio_date');
        $this->addColumnIndex('biometric_logs', 'user_id');
        $this->addColumnIndex('biometric_logs', 'transaction_date');

        $this->addColumnIndex('holidays', 'holiday_date');

        $this->addColumnIndex('department_approvers', 'employee_id');
        $this->addColumnIndex('department_approvers', 'department_id');

        $this->addColumnIndex('employee_leaves', 'employee_id');
        $this->addColumnIndex('employee_leaves', 'year');

        $this->addColumnIndex('kpi', 'department_id');
        $this->addColumnIndex('kpi', 'category');
        $this->addColumnIndex('kpi', ['evaluation_period', 'department_id']);
        $this->addColumnIndex('metrics', 'kpi_id');
        $this->addColumnIndex('data_input', 'metric_id');
        $this->addColumnIndex('kpi_datainput_result', ['user_id', 'month', 'year']);
        $this->addColumnIndex('kpi_datainput_result', 'data_input_id');
        $this->addColumnIndex('kpi_per_designation', 'designation_id');
        $this->addColumnIndex('kpi_per_designation', 'kpi_id');
        $this->addColumnIndex('evaluation_files', 'employee_id');
    }

    public function down(): void
    {
        // Drop indexes by Laravel convention: table_column_index or table_col1_col2_index
        $drops = [
            'users' => ['department_id', 'designation_id', 'user_type', 'status', 'user_type_status', 'source'],
            'gatepass' => ['user_id', 'status', 'date_filed', 'item_type', 'item_status'],
            'notice_slip' => ['user_id', 'status', 'leave_type_id', 'date_from', 'date_to'],
            'examinee_answers' => ['examinee_id', 'exam_id', 'exam_type_id', 'isCorrect'],
            'biometrics' => ['employee_id', 'bio_date'],
            'biometric_logs' => ['user_id', 'transaction_date'],
            'holidays' => ['holiday_date'],
            'department_approvers' => ['employee_id', 'department_id'],
            'employee_leaves' => ['employee_id', 'year'],
        ];
        foreach ($drops as $table => $cols) {
            if (! Schema::hasTable($table)) {
                continue;
            }
            foreach ($cols as $col) {
                $name = $table.'_'.$col.'_index';
                try {
                    Schema::table($table, fn (Blueprint $t) => $t->dropIndex($name));
                } catch (\Throwable $e) {
                    // skip if index name differs or missing
                }
            }
        }
    }

    private function addColumnIndex(string $table, string|array $columns): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }
        $cols = is_array($columns) ? $columns : [$columns];
        foreach ($cols as $c) {
            if (! Schema::hasColumn($table, $c)) {
                return;
            }
        }
        try {
            Schema::table($table, function (Blueprint $t) use ($columns) {
                if (is_array($columns)) {
                    $t->index($columns);
                } else {
                    $t->index($columns);
                }
            });
        } catch (\Throwable $e) {
            // Skip if index already exists or ALTER fails (e.g. ENUM/table state issues on gatepass)
            report($e);
        }
    }
};
