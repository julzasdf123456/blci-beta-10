<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateORAssigningRequest;
use App\Http\Requests\UpdateORAssigningRequest;
use App\Repositories\ORAssigningRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\IDGenerator;
use App\Models\ORAssigning;
use Illuminate\Support\Facades\DB;   
use Illuminate\Support\Facades\Auth; 
use Flash;
use Response;

class ORAssigningController extends AppBaseController
{
    /** @var  ORAssigningRepository */
    private $oRAssigningRepository;

    public function __construct(ORAssigningRepository $oRAssigningRepo)
    {
        $this->middleware('auth');
        $this->oRAssigningRepository = $oRAssigningRepo;
    }

    /**
     * Display a listing of the ORAssigning.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $oRAssignings = $this->oRAssigningRepository->all();

        return view('o_r_assignings.index')
            ->with('oRAssignings', $oRAssignings);
    }

    /**
     * Show the form for creating a new ORAssigning.
     *
     * @return Response
     */
    public function create()
    {
        return view('o_r_assignings.create');
    }

    /**
     * Store a newly created ORAssigning in storage.
     *
     * @param CreateORAssigningRequest $request
     *
     * @return Response
     */
    public function store(CreateORAssigningRequest $request)
    {
        $input = $request->all();

        $oRAssigning = $this->oRAssigningRepository->create($input);

        // Flash::success('O R Assigning saved successfully.');

        // return redirect(route('oRAssignings.index'));
        return response()->json($oRAssigning, 200);
    }

    /**
     * Display the specified ORAssigning.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $oRAssigning = $this->oRAssigningRepository->find($id);

        if (empty($oRAssigning)) {
            Flash::error('O R Assigning not found');

            return redirect(route('oRAssignings.index'));
        }

        return view('o_r_assignings.show')->with('oRAssigning', $oRAssigning);
    }

    /**
     * Show the form for editing the specified ORAssigning.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $oRAssigning = $this->oRAssigningRepository->find($id);

        if (empty($oRAssigning)) {
            Flash::error('O R Assigning not found');

            return redirect(route('oRAssignings.index'));
        }

        return view('o_r_assignings.edit')->with('oRAssigning', $oRAssigning);
    }

    /**
     * Update the specified ORAssigning in storage.
     *
     * @param int $id
     * @param UpdateORAssigningRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateORAssigningRequest $request)
    {
        $oRAssigning = $this->oRAssigningRepository->find($id);

        if (empty($oRAssigning)) {
            Flash::error('O R Assigning not found');

            return redirect(route('oRAssignings.index'));
        }

        $oRAssigning = $this->oRAssigningRepository->update($request->all(), $id);

        Flash::success('O R Assigning updated successfully.');

        return redirect(route('oRAssignings.index'));
    }

    /**
     * Remove the specified ORAssigning from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $oRAssigning = $this->oRAssigningRepository->find($id);

        if (empty($oRAssigning)) {
            Flash::error('O R Assigning not found');

            return redirect(route('oRAssignings.index'));
        }

        $this->oRAssigningRepository->delete($id);

        Flash::success('O R Assigning deleted successfully.');

        return redirect(route('oRAssignings.index'));
    }

    public function getLastOR() {
        $orAssignedLast = ORAssigning::where('UserId', Auth::id())
            ->orderByDesc('created_at')
            ->orderByDesc(Auth::id())
            ->first();

        return response()->json($orAssignedLast, 200);
    }

    public function getNextOR() {
        $orAssignedLast = ORAssigning::where('UserId', Auth::id())
            ->orderByDesc('created_at')
            ->orderByDesc(Auth::id())
            ->first();

        $orNext = intval($orAssignedLast->ORNumber) + 1;

        return response()->json(['ORNumber' => $orNext], 200);
    }
}
