<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTownsRequest;
use App\Http\Requests\UpdateTownsRequest;
use App\Repositories\TownsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class TownsController extends AppBaseController
{
    /** @var  TownsRepository */
    private $townsRepository;

    public function __construct(TownsRepository $townsRepo)
    {
        $this->middleware('auth');
        $this->townsRepository = $townsRepo;
    }

    /**
     * Display a listing of the Towns.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $towns = $this->townsRepository->all();

        return view('towns.index')
            ->with('towns', $towns);
    }

    /**
     * Show the form for creating a new Towns.
     *
     * @return Response
     */
    public function create()
    {
        return view('towns.create');
    }

    /**
     * Store a newly created Towns in storage.
     *
     * @param CreateTownsRequest $request
     *
     * @return Response
     */
    public function store(CreateTownsRequest $request)
    {
        $input = $request->all();

        $towns = $this->townsRepository->create($input);

        Flash::success('Towns saved successfully.');

        return redirect(route('towns.index'));
    }

    /**
     * Display the specified Towns.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $towns = $this->townsRepository->find($id);

        if (empty($towns)) {
            Flash::error('Towns not found');

            return redirect(route('towns.index'));
        }

        return view('towns.show')->with('towns', $towns);
    }

    /**
     * Show the form for editing the specified Towns.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $towns = $this->townsRepository->find($id);

        if (empty($towns)) {
            Flash::error('Towns not found');

            return redirect(route('towns.index'));
        }

        return view('towns.edit')->with('towns', $towns);
    }

    /**
     * Update the specified Towns in storage.
     *
     * @param int $id
     * @param UpdateTownsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTownsRequest $request)
    {
        $towns = $this->townsRepository->find($id);

        if (empty($towns)) {
            Flash::error('Towns not found');

            return redirect(route('towns.index'));
        }

        $towns = $this->townsRepository->update($request->all(), $id);

        Flash::success('Towns updated successfully.');

        return redirect(route('towns.index'));
    }

    /**
     * Remove the specified Towns from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $towns = $this->townsRepository->find($id);

        if (empty($towns)) {
            Flash::error('Towns not found');

            return redirect(route('towns.index'));
        }

        $this->townsRepository->delete($id);

        Flash::success('Towns deleted successfully.');

        return redirect(route('towns.index'));
    }
}
