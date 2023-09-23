<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateThirdPartyTokensRequest;
use App\Http\Requests\UpdateThirdPartyTokensRequest;
use App\Repositories\ThirdPartyTokensRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\ThirdPartyTokens;
use App\Models\IDGenerator;
use Flash;
use Response;

class ThirdPartyTokensController extends AppBaseController
{
    /** @var  ThirdPartyTokensRepository */
    private $thirdPartyTokensRepository;

    public function __construct(ThirdPartyTokensRepository $thirdPartyTokensRepo)
    {
        $this->middleware('auth');
        $this->thirdPartyTokensRepository = $thirdPartyTokensRepo;
    }

    /**
     * Display a listing of the ThirdPartyTokens.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $thirdPartyTokens = $this->thirdPartyTokensRepository->all();

        return view('third_party_tokens.index')
            ->with('thirdPartyTokens', $thirdPartyTokens);
    }

    /**
     * Show the form for creating a new ThirdPartyTokens.
     *
     * @return Response
     */
    public function create()
    {
        return view('third_party_tokens.create');
    }

    /**
     * Store a newly created ThirdPartyTokens in storage.
     *
     * @param CreateThirdPartyTokensRequest $request
     *
     * @return Response
     */
    public function store(CreateThirdPartyTokensRequest $request)
    {
        $input = $request->all();

        $thirdPartyTokens = $this->thirdPartyTokensRepository->create($input);

        Flash::success('Third Party Tokens saved successfully.');

        return redirect(route('thirdPartyTokens.index'));
    }

    /**
     * Display the specified ThirdPartyTokens.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $thirdPartyTokens = $this->thirdPartyTokensRepository->find($id);

        if (empty($thirdPartyTokens)) {
            Flash::error('Third Party Tokens not found');

            return redirect(route('thirdPartyTokens.index'));
        }

        return view('third_party_tokens.show')->with('thirdPartyTokens', $thirdPartyTokens);
    }

    /**
     * Show the form for editing the specified ThirdPartyTokens.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $thirdPartyTokens = $this->thirdPartyTokensRepository->find($id);

        if (empty($thirdPartyTokens)) {
            Flash::error('Third Party Tokens not found');

            return redirect(route('thirdPartyTokens.index'));
        }

        return view('third_party_tokens.edit')->with('thirdPartyTokens', $thirdPartyTokens);
    }

    /**
     * Update the specified ThirdPartyTokens in storage.
     *
     * @param int $id
     * @param UpdateThirdPartyTokensRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateThirdPartyTokensRequest $request)
    {
        $thirdPartyTokens = $this->thirdPartyTokensRepository->find($id);

        if (empty($thirdPartyTokens)) {
            Flash::error('Third Party Tokens not found');

            return redirect(route('thirdPartyTokens.index'));
        }

        $thirdPartyTokens = $this->thirdPartyTokensRepository->update($request->all(), $id);

        Flash::success('Third Party Tokens updated successfully.');

        return redirect(route('thirdPartyTokens.index'));
    }

    /**
     * Remove the specified ThirdPartyTokens from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $thirdPartyTokens = $this->thirdPartyTokensRepository->find($id);

        if (empty($thirdPartyTokens)) {
            Flash::error('Third Party Tokens not found');

            return redirect(route('thirdPartyTokens.index'));
        }

        $this->thirdPartyTokensRepository->delete($id);

        Flash::success('Third Party Tokens deleted successfully.');

        return redirect(route('thirdPartyTokens.index'));
    }

    public function regenerateToken($id) {
        $token = ThirdPartyTokens::find($id);

        if ($token != null) {
            $token->ThirdPartyToken = IDGenerator::generateRandString(48);
            $token->save();
        }

        return redirect(route('thirdPartyTokens.index'));
    }
}
