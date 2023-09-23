<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;
use App\Models\TicketsRepository;
use Illuminate\Support\Facades\DB;
use Validator;

class TicketrepositoriesController extends Controller {

    public $successStatus = 200;

    public function getTicketTypes() {
        $ticketTypes = TicketsRepository::all();

        return response()->json($ticketTypes, $this->successStatus);
    }
}