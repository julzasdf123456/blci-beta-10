<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMastPolesRequest;
use App\Http\Requests\UpdateMastPolesRequest;
use App\Repositories\MastPolesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class MastPolesController extends AppBaseController
{
    /** @var  MastPolesRepository */
    private $mastPolesRepository;

    public function __construct(MastPolesRepository $mastPolesRepo)
    {
        $this->middleware('auth');
        $this->mastPolesRepository = $mastPolesRepo;
    }

    /**
     * Display a listing of the MastPoles.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $mastPoles = $this->mastPolesRepository->all();

        return view('mast_poles.index')
            ->with('mastPoles', $mastPoles);
    }

    /**
     * Show the form for creating a new MastPoles.
     *
     * @return Response
     */
    public function create()
    {
        return view('mast_poles.create');
    }

    /**
     * Store a newly created MastPoles in storage.
     *
     * @param CreateMastPolesRequest $request
     *
     * @return Response
     */
    public function store(CreateMastPolesRequest $request)
    {
        $input = $request->all();

        $mastPoles = $this->mastPolesRepository->create($input);

        Flash::success('Mast Poles saved successfully.');

        return redirect(route('mastPoles.index'));
    }

    /**
     * Display the specified MastPoles.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $mastPoles = $this->mastPolesRepository->find($id);

        if (empty($mastPoles)) {
            Flash::error('Mast Poles not found');

            return redirect(route('mastPoles.index'));
        }

        return view('mast_poles.show')->with('mastPoles', $mastPoles);
    }

    /**
     * Show the form for editing the specified MastPoles.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $mastPoles = $this->mastPolesRepository->find($id);

        if (empty($mastPoles)) {
            Flash::error('Mast Poles not found');

            return redirect(route('mastPoles.index'));
        }

        return view('mast_poles.edit')->with('mastPoles', $mastPoles);
    }

    /**
     * Update the specified MastPoles in storage.
     *
     * @param int $id
     * @param UpdateMastPolesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMastPolesRequest $request)
    {
        $mastPoles = $this->mastPolesRepository->find($id);

        if (empty($mastPoles)) {
            Flash::error('Mast Poles not found');

            return redirect(route('mastPoles.index'));
        }

        $mastPoles = $this->mastPolesRepository->update($request->all(), $id);

        Flash::success('Mast Poles updated successfully.');

        return redirect(route('mastPoles.index'));
    }

    /**
     * Remove the specified MastPoles from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $mastPoles = $this->mastPolesRepository->find($id);

        if (empty($mastPoles)) {
            Flash::error('Mast Poles not found');

            return redirect(route('mastPoles.index'));
        }

        $this->mastPolesRepository->delete($id);

        Flash::success('Mast Poles deleted successfully.');

        return redirect(route('mastPoles.index'));
    }
}
