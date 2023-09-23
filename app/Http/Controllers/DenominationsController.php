<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDenominationsRequest;
use App\Http\Requests\UpdateDenominationsRequest;
use App\Repositories\DenominationsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class DenominationsController extends AppBaseController
{
    /** @var  DenominationsRepository */
    private $denominationsRepository;

    public function __construct(DenominationsRepository $denominationsRepo)
    {
        $this->middleware('auth');
        $this->denominationsRepository = $denominationsRepo;
    }

    /**
     * Display a listing of the Denominations.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $denominations = $this->denominationsRepository->all();

        return view('denominations.index')
            ->with('denominations', $denominations);
    }

    /**
     * Show the form for creating a new Denominations.
     *
     * @return Response
     */
    public function create()
    {
        return view('denominations.create');
    }

    /**
     * Store a newly created Denominations in storage.
     *
     * @param CreateDenominationsRequest $request
     *
     * @return Response
     */
    public function store(CreateDenominationsRequest $request)
    {
        $input = $request->all();

        $denominations = $this->denominationsRepository->create($input);

        Flash::success('Denominations saved successfully.');

        return redirect(route('denominations.index'));
    }

    /**
     * Display the specified Denominations.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $denominations = $this->denominationsRepository->find($id);

        if (empty($denominations)) {
            Flash::error('Denominations not found');

            return redirect(route('denominations.index'));
        }

        return view('denominations.show')->with('denominations', $denominations);
    }

    /**
     * Show the form for editing the specified Denominations.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $denominations = $this->denominationsRepository->find($id);

        if (empty($denominations)) {
            Flash::error('Denominations not found');

            return redirect(route('denominations.index'));
        }

        return view('denominations.edit')->with('denominations', $denominations);
    }

    /**
     * Update the specified Denominations in storage.
     *
     * @param int $id
     * @param UpdateDenominationsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDenominationsRequest $request)
    {
        $denominations = $this->denominationsRepository->find($id);

        if (empty($denominations)) {
            Flash::error('Denominations not found');

            return redirect(route('denominations.index'));
        }

        $denominations = $this->denominationsRepository->update($request->all(), $id);

        Flash::success('Denominations updated successfully.');

        return redirect(route('denominations.index'));
    }

    /**
     * Remove the specified Denominations from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $denominations = $this->denominationsRepository->find($id);

        if (empty($denominations)) {
            Flash::error('Denominations not found');

            return redirect(route('denominations.index'));
        }

        $this->denominationsRepository->delete($id);

        Flash::success('Denominations deleted successfully.');

        return redirect(route('denominations.index'));
    }
}
