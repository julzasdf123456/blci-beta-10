<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProjectCodesRequest;
use App\Http\Requests\UpdateProjectCodesRequest;
use App\Repositories\ProjectCodesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ProjectCodesController extends AppBaseController
{
    /** @var  ProjectCodesRepository */
    private $projectCodesRepository;

    public function __construct(ProjectCodesRepository $projectCodesRepo)
    {
        $this->middleware('auth');
        $this->projectCodesRepository = $projectCodesRepo;
    }

    /**
     * Display a listing of the ProjectCodes.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $projectCodes = $this->projectCodesRepository->all();

        return view('project_codes.index')
            ->with('projectCodes', $projectCodes);
    }

    /**
     * Show the form for creating a new ProjectCodes.
     *
     * @return Response
     */
    public function create()
    {
        return view('project_codes.create');
    }

    /**
     * Store a newly created ProjectCodes in storage.
     *
     * @param CreateProjectCodesRequest $request
     *
     * @return Response
     */
    public function store(CreateProjectCodesRequest $request)
    {
        $input = $request->all();

        $projectCodes = $this->projectCodesRepository->create($input);

        Flash::success('Project Codes saved successfully.');

        return redirect(route('projectCodes.index'));
    }

    /**
     * Display the specified ProjectCodes.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $projectCodes = $this->projectCodesRepository->find($id);

        if (empty($projectCodes)) {
            Flash::error('Project Codes not found');

            return redirect(route('projectCodes.index'));
        }

        return view('project_codes.show')->with('projectCodes', $projectCodes);
    }

    /**
     * Show the form for editing the specified ProjectCodes.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $projectCodes = $this->projectCodesRepository->find($id);

        if (empty($projectCodes)) {
            Flash::error('Project Codes not found');

            return redirect(route('projectCodes.index'));
        }

        return view('project_codes.edit')->with('projectCodes', $projectCodes);
    }

    /**
     * Update the specified ProjectCodes in storage.
     *
     * @param int $id
     * @param UpdateProjectCodesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProjectCodesRequest $request)
    {
        $projectCodes = $this->projectCodesRepository->find($id);

        if (empty($projectCodes)) {
            Flash::error('Project Codes not found');

            return redirect(route('projectCodes.index'));
        }

        $projectCodes = $this->projectCodesRepository->update($request->all(), $id);

        Flash::success('Project Codes updated successfully.');

        return redirect(route('projectCodes.index'));
    }

    /**
     * Remove the specified ProjectCodes from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $projectCodes = $this->projectCodesRepository->find($id);

        if (empty($projectCodes)) {
            Flash::error('Project Codes not found');

            return redirect(route('projectCodes.index'));
        }

        $this->projectCodesRepository->delete($id);

        Flash::success('Project Codes deleted successfully.');

        return redirect(route('projectCodes.index'));
    }
}
