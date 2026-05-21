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
        if (! $request->from || ! is_array($request->from) || count($request->from) === 0) {
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

        try {
            $todays_date = Carbon::now()->format('Y-m-d H:i:s');
            $list = DB::connection('mysql_erp')->table('tabItinerary')->where('name', 'like', '%ITK%')
                ->select(DB::raw('MAX(CAST(SUBSTRING(name, 4, length(name)-3) AS UNSIGNED)) as name'))
                ->first();

            $last_id = $list->name ? $list->name : 0;
            $new_id = 'ITK'.str_pad((string) ($last_id + 1), 4, '0', STR_PAD_LEFT);

            $itk = [
                'name' => $new_id,
                'creation' => $todays_date,
                'modified' => $todays_date,
                'modified_by' => $user->employee_name,
                'owner' => $user->employee_name,
                'docstatus' => 0,
                'workflow_state' => 'For Approval',
                'transaction_date' => date('Y-m-d'),
            ];

            DB::connection('mysql_erp')->table('tabItinerary')->insert($itk);

            $itk_child = [];
            foreach ($request->from as $i => $row) {
                $customer = ($request->from[$i] == 'Customer') ? $request->destination[$i] : null;
                $lead = ($request->from[$i] == 'Lead') ? $request->destination[$i] : null;
                $supplier = ($request->from[$i] == 'Supplier') ? $request->destination[$i] : null;
                $others = ($request->from[$i] == 'Others') ? $request->destination[$i] : null;

                $parsed = DateTime::createFromFormat('m-d-Y', $request->itinerary_date[$i]);
                if (! $parsed) {
                    return [
                        'success' => false,
                        'itinerary_id' => null,
                        'message' => 'Invalid itinerary date format. Use MM-DD-YYYY.',
                    ];
                }
                $itinerary_date = $parsed->format('Y-m-d');

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
                    'project' => $request->project[$i],
                    'customer' => $customer,
                    'itinerary_date' => $itinerary_date,
                    'purpose' => $request->purpose[$i],
                    'time' => $request->itinerary_time[$i],
                    'from' => $request->from[$i],
                    'lead' => $lead,
                    'supplier' => $supplier,
                    'destination' => $others,
                    'itinerary_location' => $request->destination[$i],
                ];
            }

            DB::connection('mysql_erp')->table('tabItinerary Tab')->insert($itk_child);

            if ($request->companion_id) {
                $companion = [];
                foreach ($request->companion_id as $i => $row) {
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
                        'companion' => $request->companion_id[$i],
                        'employee_name' => $request->companion_name[$i],
                    ];
                }

                DB::connection('mysql_erp')->table('tabCompanion Table')->insert($companion);
            }

            return [
                'success' => true,
                'itinerary_id' => $new_id,
                'message' => 'Itinerary '.$new_id.' submitted for approval.',
            ];
        } catch (Throwable $e) {
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
}
