<?php

namespace App\Http\Controllers;

use App\Services\ItinerarySubmissionService;
use Auth;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItineraryController extends Controller
{
    public function __construct(
        private readonly ItinerarySubmissionService $itinerarySubmissionService
    ) {}

    public function fetchItineraries()
    {
        $list = DB::connection('mysql_erp')
            ->table('tabItinerary Tab')
            ->join('tabItinerary', 'tabItinerary.name', '=', 'tabItinerary Tab.parent')
            ->select('tabItinerary.workflow_state', 'tabItinerary Tab.*')
            ->where(function ($query) {
                $query->where('tabItinerary Tab.owner', Auth::user()->email)
                    ->orWhere('tabItinerary Tab.owner', Auth::user()->employee_name);
            })
            ->orderBy('creation', 'desc')
            ->paginate(8);

        return view('client.tables.itinerary_table', compact('list'));
    }

    public function fetchItineraries_companion(Request $request)
    {
        $itr_companion = DB::connection('mysql_erp')->table('tabCompanion Table')
            ->where('parent', $request->id)->get();

        return view('client.tables.itinerary_companion', compact('itr_companion'));
    }

    public function store(Request $request): JsonResponse
    {
        $result = $this->itinerarySubmissionService->submit($request);

        return response()->json($result);
    }

    public function destinations(string $doctype): JsonResponse
    {
        return response()->json($this->itinerarySubmissionService->getDocList($doctype));
    }

    public function employees(): JsonResponse
    {
        return response()->json($this->itinerarySubmissionService->getEmployees());
    }
}
