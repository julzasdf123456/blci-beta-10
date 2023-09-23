<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Notifications;
use Validator;

class SMSNotificationsAPI extends Controller {

    public $successStatus = 200;

    public function getRandomNotification(Request $request) {
        $sms = DB::table('SMS_Notifications')
            ->select('*')
            ->where('Status', 'PENDING')
            ->orderBy('created_at')
            ->first();

        if ($sms != null) {
            return response()->json($sms, $this->successStatus);
        } else {
            return response()->json(['res' => 'No notifications found'], 404);
        }
    }

    public function updateSMSNotification(Request $request) {
        $id = $request['id'];
        $status = $request['Status'];

        Notifications::where('id', $id)
            ->update(['Status' => $status]);

        return response()->json('ok', 200);
    }
}