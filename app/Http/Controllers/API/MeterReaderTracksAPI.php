<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request; 
use App\Models\MeterReaderTrackNames;
use App\Models\MeterReaderTracks;
use App\Models\IDGenerator;

class MeterReaderTracksAPI extends Controller {
    public $successStatus = 200;

    public function saveTrackNames(Request $request) {
        $trackNames = MeterReaderTrackNames::where('TrackName', $request['TrackName'])->first();

        if ($trackNames != null) {
            return response()->json(['res' => 'exists'], $this->successStatus);
        } else {
            $trackNames = new MeterReaderTrackNames;
            $trackNames->id = $request['id'];
            $trackNames->TrackName = $request['TrackName'];
            $trackNames->save();

            return response()->json(['res' => 'ok'], $this->successStatus);
        }
    }

    public function saveTracks(Request $request) {
        $track = MeterReaderTracks::where('Latitude', $request['Latitude'])
                    ->where('Longitude', $request['Longitude'])
                    ->where('Captured', $request['created_at'])
                    ->first();

        if ($track != null) {
            return response()->json(['res' => 'exists'], $this->successStatus);
        } else {
            $track = new MeterReaderTracks;
            $track->id = IDGenerator::generateIDandRandString(35);
            $track->TrackNameId = $request['TrackNameId'];
            $track->Latitude = $request['Latitude'];
            $track->Longitude = $request['Longitude'];
            $track->Captured = $request['created_at'];
            $track->save();

            return response()->json(['res' => 'ok'], $this->successStatus);
        }
    }

    public function getDownloadableTrackNames() {
        $trackNames = MeterReaderTrackNames::all();

        return response()->json($trackNames, 200);
    }

    public function getDownloadableTracks(Request $request) {
        $tracks = MeterReaderTracks::where('TrackNameId', $request['TrackNameId'])->get();

        return response()->json($tracks, 200);
    }
}

?>