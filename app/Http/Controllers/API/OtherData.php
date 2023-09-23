<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ServiceConnectionCrew;
use App\Models\Towns;
use App\Models\Barangays;

class OtherData extends Controller {
    public $successStatus = 200;

    public function getTowns() {
        $towns = Towns::all();

        if ($towns == null) {
            return response()->json(['error' => 'No data'], 404); 
        } else {
            return response()->json($towns, $this->successStatus); 
        } 
    }

    public function getBarangays() {
        $barangays = Barangays::all();

        if ($barangays == null) {
            return response()->json(['error' => 'No data'], 404); 
        } else {
            return response()->json($barangays, $this->successStatus); 
        } 
    }

    public function getAllCrew() {
        $crew = ServiceConnectionCrew::all();

        if ($crew) {
            return response()->json($crew, $this->successStatus);
        } else {
            return response()->json(['response' => 'No data'], 404);
        }
    }
}