<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSignatoriesRequest;
use App\Http\Requests\UpdateSignatoriesRequest;
use App\Repositories\SignatoriesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Towns;
use App\Models\Signatories;
use Flash;
use Response;

class SignatoriesController extends AppBaseController
{
    /** @var  SignatoriesRepository */
    private $signatoriesRepository;

    public function __construct(SignatoriesRepository $signatoriesRepo)
    {
        $this->middleware('auth');
        $this->signatoriesRepository = $signatoriesRepo;
    }

    /**
     * Display a listing of the Signatories.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $signatories = Signatories::whereNotIn('Notes', ['TICKETS'])->get();

        return view('signatories.index')
            ->with('signatories', $signatories);
    }

    /**
     * Show the form for creating a new Signatories.
     *
     * @return Response
     */
    public function create()
    {

        return view('signatories.create', [
            'towns' => Towns::orderBy('Town')->get(),
        ]);
    }

    /**
     * Store a newly created Signatories in storage.
     *
     * @param CreateSignatoriesRequest $request
     *
     * @return Response
     */
    public function store(CreateSignatoriesRequest $request)
    {
        $input = $request->all();

        $allowed_ext= array('jpg','jpeg','png');
        $file_name =$_FILES['RawSign']['name'];
    //   $file_name =$_FILES['RawSign']['tmp_name'];
        // $file_ext = strtolower( end(explode('.',$file_name)));


        $file_size=$_FILES['RawSign']['size'];
        $file_tmp= $_FILES['RawSign']['tmp_name'];
        // echo $file_tmp;echo "<br>";

        $type = pathinfo($file_tmp, PATHINFO_EXTENSION);
        $data = file_get_contents($file_tmp);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        // echo "Base64 is ".$base64;

        $input['Signature'] = $base64;

        $signatories = $this->signatoriesRepository->create($input);

        Flash::success('Signatories saved successfully.');

        return redirect(route('signatories.index'));
    }

    /**
     * Display the specified Signatories.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $signatories = $this->signatoriesRepository->find($id);

        if (empty($signatories)) {
            Flash::error('Signatories not found');

            return redirect(route('signatories.index'));
        }

        return view('signatories.show')->with('signatories', $signatories);
    }

    /**
     * Show the form for editing the specified Signatories.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $signatories = $this->signatoriesRepository->find($id);

        if (empty($signatories)) {
            Flash::error('Signatories not found');

            return redirect(route('signatories.index'));
        }

        return view('signatories.edit', [            
            'towns' => Towns::orderBy('Town')->get(),
            'signatories' => $signatories,
        ]);
    }

    /**
     * Update the specified Signatories in storage.
     *
     * @param int $id
     * @param UpdateSignatoriesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSignatoriesRequest $request)
    {
        $signatories = $this->signatoriesRepository->find($id);

        if (empty($signatories)) {
            Flash::error('Signatories not found');

            return redirect(route('signatories.index'));
        }

        $signatories = $this->signatoriesRepository->update($request->all(), $id);

        Flash::success('Signatories updated successfully.');

        return redirect(route('signatories.index'));
    }

    /**
     * Remove the specified Signatories from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $signatories = $this->signatoriesRepository->find($id);

        if (empty($signatories)) {
            Flash::error('Signatories not found');

            return redirect(route('signatories.index'));
        }

        $this->signatoriesRepository->delete($id);

        Flash::success('Signatories deleted successfully.');

        return redirect(route('signatories.index'));
    }
}
