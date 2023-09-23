<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateNotifiersRequest;
use App\Http\Requests\UpdateNotifiersRequest;
use App\Repositories\NotifiersRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Notifiers;
use Flash;
use Response;

class NotifiersController extends AppBaseController
{
    /** @var  NotifiersRepository */
    private $notifiersRepository;

    public function __construct(NotifiersRepository $notifiersRepo)
    {
        $this->middleware('auth');
        $this->notifiersRepository = $notifiersRepo;
    }

    /**
     * Display a listing of the Notifiers.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $notifiers = $this->notifiersRepository->all();

        return view('notifiers.index')
            ->with('notifiers', $notifiers);
    }

    /**
     * Show the form for creating a new Notifiers.
     *
     * @return Response
     */
    public function create()
    {
        return view('notifiers.create');
    }

    /**
     * Store a newly created Notifiers in storage.
     *
     * @param CreateNotifiersRequest $request
     *
     * @return Response
     */
    public function store(CreateNotifiersRequest $request)
    {
        $input = $request->all();

        $notifiers = $this->notifiersRepository->create($input);

        Flash::success('Notifiers saved successfully.');

        return redirect(route('notifiers.index'));
    }

    /**
     * Display the specified Notifiers.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $notifiers = $this->notifiersRepository->find($id);

        if (empty($notifiers)) {
            Flash::error('Notifiers not found');

            return redirect(route('notifiers.index'));
        }

        return view('notifiers.show')->with('notifiers', $notifiers);
    }

    /**
     * Show the form for editing the specified Notifiers.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $notifiers = $this->notifiersRepository->find($id);

        if (empty($notifiers)) {
            Flash::error('Notifiers not found');

            return redirect(route('notifiers.index'));
        }

        return view('notifiers.edit')->with('notifiers', $notifiers);
    }

    /**
     * Update the specified Notifiers in storage.
     *
     * @param int $id
     * @param UpdateNotifiersRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateNotifiersRequest $request)
    {
        $notifiers = $this->notifiersRepository->find($id);

        if (empty($notifiers)) {
            Flash::error('Notifiers not found');

            return redirect(route('notifiers.index'));
        }

        $notifiers = $this->notifiersRepository->update($request->all(), $id);

        Flash::success('Notifiers updated successfully.');

        return redirect(route('notifiers.index'));
    }

    /**
     * Remove the specified Notifiers from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $notifiers = $this->notifiersRepository->find($id);

        if (empty($notifiers)) {
            Flash::error('Notifiers not found');

            return redirect(route('notifiers.index'));
        }

        $this->notifiersRepository->delete($id);

        Flash::success('Notifiers deleted successfully.');

        return redirect(route('notifiers.index'));
    }

    public function getNotifications() {
        $notifiers = Notifiers::whereRaw("[To]='" . Auth::id() . "'")
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $output = "";
        foreach($notifiers as $item) {
            if ($item->Intent == 'OR CANCELLATION') {
                $output .= '<div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; ">' . $item->Notification . '
                                <span class="float-right text-muted text-sm">' . date('M d, Y h:i:s A', strtotime($item->created_at)) . '</span>
                            </a>';
            } else if ($item->Intent == 'BILL ARREAR PAYMENT UNLOCKING') {
                $output .= '<div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; ">' . $item->Notification . '
                            <span class="float-right text-muted text-sm">' . date('M d, Y h:i:s A', strtotime($item->created_at)) . '</span>
                            </a>';
            }
        }

        return response()->json($output, 200);
    }
}
