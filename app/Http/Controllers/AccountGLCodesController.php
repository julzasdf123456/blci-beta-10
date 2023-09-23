<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAccountGLCodesRequest;
use App\Http\Requests\UpdateAccountGLCodesRequest;
use App\Repositories\AccountGLCodesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class AccountGLCodesController extends AppBaseController
{
    /** @var  AccountGLCodesRepository */
    private $accountGLCodesRepository;

    public function __construct(AccountGLCodesRepository $accountGLCodesRepo)
    {
        $this->middleware('auth');
        $this->accountGLCodesRepository = $accountGLCodesRepo;
    }

    /**
     * Display a listing of the AccountGLCodes.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $accountGLCodes = $this->accountGLCodesRepository->all();

        return view('account_g_l_codes.index')
            ->with('accountGLCodes', $accountGLCodes);
    }

    /**
     * Show the form for creating a new AccountGLCodes.
     *
     * @return Response
     */
    public function create()
    {
        return view('account_g_l_codes.create');
    }

    /**
     * Store a newly created AccountGLCodes in storage.
     *
     * @param CreateAccountGLCodesRequest $request
     *
     * @return Response
     */
    public function store(CreateAccountGLCodesRequest $request)
    {
        $input = $request->all();

        $accountGLCodes = $this->accountGLCodesRepository->create($input);

        Flash::success('Account G L Codes saved successfully.');

        return redirect(route('accountGLCodes.index'));
    }

    /**
     * Display the specified AccountGLCodes.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $accountGLCodes = $this->accountGLCodesRepository->find($id);

        if (empty($accountGLCodes)) {
            Flash::error('Account G L Codes not found');

            return redirect(route('accountGLCodes.index'));
        }

        return view('account_g_l_codes.show')->with('accountGLCodes', $accountGLCodes);
    }

    /**
     * Show the form for editing the specified AccountGLCodes.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $accountGLCodes = $this->accountGLCodesRepository->find($id);

        if (empty($accountGLCodes)) {
            Flash::error('Account G L Codes not found');

            return redirect(route('accountGLCodes.index'));
        }

        return view('account_g_l_codes.edit')->with('accountGLCodes', $accountGLCodes);
    }

    /**
     * Update the specified AccountGLCodes in storage.
     *
     * @param int $id
     * @param UpdateAccountGLCodesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAccountGLCodesRequest $request)
    {
        $accountGLCodes = $this->accountGLCodesRepository->find($id);

        if (empty($accountGLCodes)) {
            Flash::error('Account G L Codes not found');

            return redirect(route('accountGLCodes.index'));
        }

        $accountGLCodes = $this->accountGLCodesRepository->update($request->all(), $id);

        Flash::success('Account G L Codes updated successfully.');

        return redirect(route('accountGLCodes.index'));
    }

    /**
     * Remove the specified AccountGLCodes from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $accountGLCodes = $this->accountGLCodesRepository->find($id);

        if (empty($accountGLCodes)) {
            Flash::error('Account G L Codes not found');

            return redirect(route('accountGLCodes.index'));
        }

        $this->accountGLCodesRepository->delete($id);

        Flash::success('Account G L Codes deleted successfully.');

        return redirect(route('accountGLCodes.index'));
    }
}
