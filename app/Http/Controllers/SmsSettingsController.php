<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSmsSettingsRequest;
use App\Http\Requests\UpdateSmsSettingsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\SmsSettingsRepository;
use Illuminate\Http\Request;
use Flash;

class SmsSettingsController extends AppBaseController
{
    /** @var SmsSettingsRepository $smsSettingsRepository*/
    private $smsSettingsRepository;

    public function __construct(SmsSettingsRepository $smsSettingsRepo)
    {
        $this->smsSettingsRepository = $smsSettingsRepo;
    }

    /**
     * Display a listing of the SmsSettings.
     */
    public function index(Request $request)
    {
        $smsSettings = $this->smsSettingsRepository->paginate(10);

        return view('sms_settings.index')
            ->with('smsSettings', $smsSettings);
    }

    /**
     * Show the form for creating a new SmsSettings.
     */
    public function create()
    {
        return view('sms_settings.create');
    }

    /**
     * Store a newly created SmsSettings in storage.
     */
    public function store(CreateSmsSettingsRequest $request)
    {
        $input = $request->all();

        $smsSettings = $this->smsSettingsRepository->create($input);

        Flash::success('Sms Settings saved successfully.');

        return redirect(route('smsSettings.index'));
    }

    /**
     * Display the specified SmsSettings.
     */
    public function show($id)
    {
        $smsSettings = $this->smsSettingsRepository->find($id);

        if (empty($smsSettings)) {
            Flash::error('Sms Settings not found');

            return redirect(route('smsSettings.index'));
        }

        return view('sms_settings.show')->with('smsSettings', $smsSettings);
    }

    /**
     * Show the form for editing the specified SmsSettings.
     */
    public function edit($id)
    {
        $smsSettings = $this->smsSettingsRepository->find($id);

        if (empty($smsSettings)) {
            Flash::error('Sms Settings not found');

            return redirect(route('smsSettings.index'));
        }

        return view('sms_settings.edit')->with('smsSettings', $smsSettings);
    }

    /**
     * Update the specified SmsSettings in storage.
     */
    public function update($id, UpdateSmsSettingsRequest $request)
    {
        $smsSettings = $this->smsSettingsRepository->find($id);

        if (empty($smsSettings)) {
            Flash::error('Sms Settings not found');

            return redirect(route('smsSettings.index'));
        }

        $smsSettings = $this->smsSettingsRepository->update($request->all(), $id);

        Flash::success('Sms Settings updated successfully.');

        return redirect(route('smsSettings.index'));
    }

    /**
     * Remove the specified SmsSettings from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $smsSettings = $this->smsSettingsRepository->find($id);

        if (empty($smsSettings)) {
            Flash::error('Sms Settings not found');

            return redirect(route('smsSettings.index'));
        }

        $this->smsSettingsRepository->delete($id);

        Flash::success('Sms Settings deleted successfully.');

        return redirect(route('smsSettings.index'));
    }
}
