<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReadingFromTextRequest;
use App\Http\Requests\UpdateReadingFromTextRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\ReadingFromTextRepository;
use Illuminate\Http\Request;
use Flash;

class ReadingFromTextController extends AppBaseController
{
    /** @var ReadingFromTextRepository $readingFromTextRepository*/
    private $readingFromTextRepository;

    public function __construct(ReadingFromTextRepository $readingFromTextRepo)
    {
        $this->middleware('auth');
        $this->readingFromTextRepository = $readingFromTextRepo;
    }

    /**
     * Display a listing of the ReadingFromText.
     */
    public function index(Request $request)
    {
        $readingFromTexts = $this->readingFromTextRepository->paginate(10);

        return view('reading_from_texts.index')
            ->with('readingFromTexts', $readingFromTexts);
    }

    /**
     * Show the form for creating a new ReadingFromText.
     */
    public function create()
    {
        return view('reading_from_texts.create');
    }

    /**
     * Store a newly created ReadingFromText in storage.
     */
    public function store(CreateReadingFromTextRequest $request)
    {
        $input = $request->all();

        $readingFromText = $this->readingFromTextRepository->create($input);

        Flash::success('Reading From Text saved successfully.');

        return redirect(route('readingFromTexts.index'));
    }

    /**
     * Display the specified ReadingFromText.
     */
    public function show($id)
    {
        $readingFromText = $this->readingFromTextRepository->find($id);

        if (empty($readingFromText)) {
            Flash::error('Reading From Text not found');

            return redirect(route('readingFromTexts.index'));
        }

        return view('reading_from_texts.show')->with('readingFromText', $readingFromText);
    }

    /**
     * Show the form for editing the specified ReadingFromText.
     */
    public function edit($id)
    {
        $readingFromText = $this->readingFromTextRepository->find($id);

        if (empty($readingFromText)) {
            Flash::error('Reading From Text not found');

            return redirect(route('readingFromTexts.index'));
        }

        return view('reading_from_texts.edit')->with('readingFromText', $readingFromText);
    }

    /**
     * Update the specified ReadingFromText in storage.
     */
    public function update($id, UpdateReadingFromTextRequest $request)
    {
        $readingFromText = $this->readingFromTextRepository->find($id);

        if (empty($readingFromText)) {
            Flash::error('Reading From Text not found');

            return redirect(route('readingFromTexts.index'));
        }

        $readingFromText = $this->readingFromTextRepository->update($request->all(), $id);

        Flash::success('Reading From Text updated successfully.');

        return redirect(route('readingFromTexts.index'));
    }

    /**
     * Remove the specified ReadingFromText from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $readingFromText = $this->readingFromTextRepository->find($id);

        if (empty($readingFromText)) {
            Flash::error('Reading From Text not found');

            return redirect(route('readingFromTexts.index'));
        }

        $this->readingFromTextRepository->delete($id);

        Flash::success('Reading From Text deleted successfully.');

        return redirect(route('readingFromTexts.index'));
    }
}
