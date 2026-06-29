<?php

declare(strict_types=1);

namespace App\Services;

use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

final class ItinerarySubmissionService
{
    /**
     * @return array{success: bool, itinerary_id: ?string, message: string}
     */
    public function submit(Request $request): array
    {
        $from = $request->input('from');

        if (! is_array($from) || count($from) === 0) {
            return [
                'success' => false,
                'itinerary_id' => null,
                'message' => 'Please add at least one itinerary stop.',
            ];
        }

        $user = Auth::user();
        if (! $user) {
            return [
                'success' => false,
                'itinerary_id' => null,
                'message' => 'You must be logged in to file an itinerary.',
            ];
        }

        $parsedDates = [];
        foreach (array_keys($from) as $i) {
            $parsed = $this->parseItineraryDate((string) ($request->input('itinerary_date')[$i] ?? ''));
            if (! $parsed) {
                return [
                    'success' => false,
                    'itinerary_id' => null,
                    'message' => 'Invalid itinerary date format. Use MM-DD-YYYY.',
                ];
            }

            $parsedDates[$i] = $parsed->format('Y-m-d');
        }

        try {
            $connection = DB::connection('mysql_erp');
            $todays_date = Carbon::now()->format('Y-m-d H:i:s');

            $connection->beginTransaction();

            $list = $connection->table('tabItinerary')->where('name', 'like', '%ITK%')
                ->select(DB::raw('MAX(CAST(SUBSTRING(name, 4, length(name)-3) AS UNSIGNED)) as name'))
                ->first();

            $last_id = $list->name ? $list->name : 0;
            $new_id = 'ITK'.str_pad((string) ($last_id + 1), 4, '0', STR_PAD_LEFT);

            $connection->table('tabItinerary')->insert([
                'name' => $new_id,
                'creation' => $todays_date,
                'modified' => $todays_date,
                'modified_by' => $user->employee_name,
                'owner' => $user->employee_name,
                'docstatus' => 0,
                'workflow_state' => 'For Approval',
                'transaction_date' => date('Y-m-d'),
            ]);

            $itk_child = [];
            foreach ($from as $i => $row) {
                $destination = $request->input('destination')[$i] ?? null;
                $customer = ($from[$i] == 'Customer') ? $destination : null;
                $lead = ($from[$i] == 'Lead') ? $destination : null;
                $supplier = ($from[$i] == 'Supplier') ? $destination : null;
                $others = ($from[$i] == 'Others') ? $destination : null;

                $itk_child[] = [
                    'name' => uniqid(date('mdY')),
                    'creation' => $todays_date,
                    'modified' => $todays_date,
                    'modified_by' => $user->employee_name,
                    'owner' => $user->employee_name,
                    'docstatus' => 0,
                    'parent' => $new_id,
                    'parentfield' => 'project',
                    'parenttype' => 'Itinerary',
                    'idx' => $i + 1,
                    'project' => $request->input('project')[$i] ?? null,
                    'customer' => $customer,
                    'itinerary_date' => $parsedDates[$i],
                    'purpose' => $request->input('purpose')[$i] ?? null,
                    'time' => $request->input('itinerary_time')[$i] ?? null,
                    'from' => $from[$i],
                    'lead' => $lead,
                    'supplier' => $supplier,
                    'destination' => $others,
                    'itinerary_location' => $destination,
                    'date' => $parsedDates[$i],
                ];
            }

            $connection->table('tabItinerary Tab')->insert($itk_child);

            $companionIds = $request->input('companion_id');
            if (is_array($companionIds) && count($companionIds) > 0) {
                $companion = [];
                foreach ($companionIds as $i => $companionId) {
                    $companion[] = [
                        'name' => uniqid(date('mdY')),
                        'creation' => $todays_date,
                        'modified' => $todays_date,
                        'modified_by' => $user->employee_name,
                        'owner' => $user->email,
                        'docstatus' => 0,
                        'parent' => $new_id,
                        'parentfield' => 'companion',
                        'parenttype' => 'Itinerary',
                        'idx' => $i + 1,
                        'companion' => $companionId,
                        'employee_name' => $request->input('companion_name')[$i] ?? null,
                    ];
                }

                $connection->table('tabCompanion Table')->insert($companion);
            }

            $connection->commit();

            return [
                'success' => true,
                'itinerary_id' => $new_id,
                'message' => 'Itinerary '.$new_id.' submitted for approval.',
            ];
        } catch (Throwable $e) {
            if (DB::connection('mysql_erp')->transactionLevel() > 0) {
                DB::connection('mysql_erp')->rollBack();
            }

            report($e);

            return [
                'success' => false,
                'itinerary_id' => null,
                'message' => 'Unable to save itinerary. Please check ERP connection or try again later.',
            ];
        }
    }

    /**
     * @return array<string, string>
     */
    public function getEmployees(): array
    {
        try {
            return DB::connection('mysql_erp')->table('tabEmployee')->where('status', 'Active')
                ->orderBy('employee_name', 'asc')->pluck('name', 'employee_name')->all();
        } catch (Throwable $e) {
            report($e);

            return [];
        }
    }

    /**
     * @return list<string>
     */
    public function getDocList(string $doctype): array
    {
        try {
            $table = 'tab'.$doctype;

            return DB::connection('mysql_erp')->table($table)->orderBy('name', 'asc')->pluck('name')->all();
        } catch (Throwable $e) {
            report($e);

            return [];
        }
    }

    private function parseItineraryDate(string $raw): ?DateTime
    {
        $value = trim($raw);
        if ($value === '') {
            return null;
        }

        foreach ([
            'm-d-Y',
            'n-j-Y',
            'm/d/Y',
            'n/j/Y',
            'm-d-Y g:i A',
            'm-d-Y H:i:s A',
            'n-j-Y g:i A',
            'Y-m-d',
        ] as $format) {
            $parsed = DateTime::createFromFormat($format, $value);
            if ($parsed instanceof DateTime) {
                return $parsed;
            }
        }

        return null;
    }
}
