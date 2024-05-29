<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSystemSettingsRequest;
use App\Http\Requests\UpdateSystemSettingsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\SystemSettingsRepository;
use Illuminate\Http\Request;
use Flash;

class SystemSettingsController extends AppBaseController
{
    /** @var SystemSettingsRepository $systemSettingsRepository*/
    private $systemSettingsRepository;

    public function __construct(SystemSettingsRepository $systemSettingsRepo)
    {
        $this->systemSettingsRepository = $systemSettingsRepo;
    }

    /**
     * Display a listing of the SystemSettings.
     */
    public function index(Request $request)
    {
        $systemSettings = $this->systemSettingsRepository->paginate(10);

        return view('system_settings.index')
            ->with('systemSettings', $systemSettings);
    }

    /**
     * Show the form for creating a new SystemSettings.
     */
    public function create()
    {
        return view('system_settings.create');
    }

    /**
     * Store a newly created SystemSettings in storage.
     */
    public function store(CreateSystemSettingsRequest $request)
    {
        $input = $request->all();

        $systemSettings = $this->systemSettingsRepository->create($input);

        Flash::success('System Settings saved successfully.');

        return redirect(route('systemSettings.index'));
    }

    /**
     * Display the specified SystemSettings.
     */
    public function show($id)
    {
        $systemSettings = $this->systemSettingsRepository->find($id);

        if (empty($systemSettings)) {
            Flash::error('System Settings not found');

            return redirect(route('systemSettings.index'));
        }

        return view('system_settings.show')->with('systemSettings', $systemSettings);
    }

    /**
     * Show the form for editing the specified SystemSettings.
     */
    public function edit($id)
    {
        $systemSettings = $this->systemSettingsRepository->find($id);

        if (empty($systemSettings)) {
            Flash::error('System Settings not found');

            return redirect(route('systemSettings.index'));
        }

        return view('system_settings.edit')->with('systemSettings', $systemSettings);
    }

    /**
     * Update the specified SystemSettings in storage.
     */
    public function update($id, UpdateSystemSettingsRequest $request)
    {
        $systemSettings = $this->systemSettingsRepository->find($id);

        if (empty($systemSettings)) {
            Flash::error('System Settings not found');

            return redirect(route('systemSettings.index'));
        }

        $systemSettings = $this->systemSettingsRepository->update($request->all(), $id);

        Flash::success('System Settings updated successfully.');

        return redirect(route('systemSettings.index'));
    }

    /**
     * Remove the specified SystemSettings from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $systemSettings = $this->systemSettingsRepository->find($id);

        if (empty($systemSettings)) {
            Flash::error('System Settings not found');

            return redirect(route('systemSettings.index'));
        }

        $this->systemSettingsRepository->delete($id);

        Flash::success('System Settings deleted successfully.');

        return redirect(route('systemSettings.index'));
    }
}
