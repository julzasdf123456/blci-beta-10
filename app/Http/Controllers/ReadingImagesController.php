<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReadingImagesRequest;
use App\Http\Requests\UpdateReadingImagesRequest;
use App\Repositories\ReadingImagesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ReadingImagesController extends AppBaseController
{
    /** @var  ReadingImagesRepository */
    private $readingImagesRepository;

    public function __construct(ReadingImagesRepository $readingImagesRepo)
    {
        $this->middleware('auth');
        $this->readingImagesRepository = $readingImagesRepo;
    }

    /**
     * Display a listing of the ReadingImages.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $readingImages = $this->readingImagesRepository->all();

        return view('reading_images.index')
            ->with('readingImages', $readingImages);
    }

    /**
     * Show the form for creating a new ReadingImages.
     *
     * @return Response
     */
    public function create()
    {
        return view('reading_images.create');
    }

    /**
     * Store a newly created ReadingImages in storage.
     *
     * @param CreateReadingImagesRequest $request
     *
     * @return Response
     */
    public function store(CreateReadingImagesRequest $request)
    {
        $input = $request->all();

        $readingImages = $this->readingImagesRepository->create($input);

        Flash::success('Reading Images saved successfully.');

        return redirect(route('readingImages.index'));
    }

    /**
     * Display the specified ReadingImages.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $readingImages = $this->readingImagesRepository->find($id);

        if (empty($readingImages)) {
            Flash::error('Reading Images not found');

            return redirect(route('readingImages.index'));
        }

        return view('reading_images.show')->with('readingImages', $readingImages);
    }

    /**
     * Show the form for editing the specified ReadingImages.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $readingImages = $this->readingImagesRepository->find($id);

        if (empty($readingImages)) {
            Flash::error('Reading Images not found');

            return redirect(route('readingImages.index'));
        }

        return view('reading_images.edit')->with('readingImages', $readingImages);
    }

    /**
     * Update the specified ReadingImages in storage.
     *
     * @param int $id
     * @param UpdateReadingImagesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateReadingImagesRequest $request)
    {
        $readingImages = $this->readingImagesRepository->find($id);

        if (empty($readingImages)) {
            Flash::error('Reading Images not found');

            return redirect(route('readingImages.index'));
        }

        $readingImages = $this->readingImagesRepository->update($request->all(), $id);

        Flash::success('Reading Images updated successfully.');

        return redirect(route('readingImages.index'));
    }

    /**
     * Remove the specified ReadingImages from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $readingImages = $this->readingImagesRepository->find($id);

        if (empty($readingImages)) {
            Flash::error('Reading Images not found');

            return redirect(route('readingImages.index'));
        }

        $this->readingImagesRepository->delete($id);

        Flash::success('Reading Images deleted successfully.');

        return redirect(route('readingImages.index'));
    }
}
