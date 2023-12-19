<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBoholWaterServiceAccountsRequest;
use App\Http\Requests\UpdateBoholWaterServiceAccountsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\BoholWaterServiceAccountsRepository;
use Illuminate\Http\Request;
use App\Models\IDGenerator;
use App\Models\BoholWaterServiceAccounts;
use Flash;

class BoholWaterServiceAccountsController extends AppBaseController
{
    /** @var BoholWaterServiceAccountsRepository $boholWaterServiceAccountsRepository*/
    private $boholWaterServiceAccountsRepository;

    public function __construct(BoholWaterServiceAccountsRepository $boholWaterServiceAccountsRepo)
    {
        $this->middleware('auth');
        $this->boholWaterServiceAccountsRepository = $boholWaterServiceAccountsRepo;
    }

    /**
     * Display a listing of the BoholWaterServiceAccounts.
     */
    public function index(Request $request)
    {
        $boholWaterServiceAccounts = $this->boholWaterServiceAccountsRepository->paginate(10);

        return view('bohol_water_service_accounts.index')
            ->with('boholWaterServiceAccounts', $boholWaterServiceAccounts);
    }

    /**
     * Show the form for creating a new BoholWaterServiceAccounts.
     */
    public function create()
    {
        return view('bohol_water_service_accounts.create');
    }

    /**
     * Store a newly created BoholWaterServiceAccounts in storage.
     */
    public function store(CreateBoholWaterServiceAccountsRequest $request)
    {
        $input = $request->all();

        $boholWaterServiceAccounts = $this->boholWaterServiceAccountsRepository->create($input);

        Flash::success('Bohol Water Service Accounts saved successfully.');

        return redirect(route('boholWaterServiceAccounts.index'));
    }

    /**
     * Display the specified BoholWaterServiceAccounts.
     */
    public function show($id)
    {
        $boholWaterServiceAccounts = $this->boholWaterServiceAccountsRepository->find($id);

        if (empty($boholWaterServiceAccounts)) {
            Flash::error('Bohol Water Service Accounts not found');

            return redirect(route('boholWaterServiceAccounts.index'));
        }

        return view('bohol_water_service_accounts.show')->with('boholWaterServiceAccounts', $boholWaterServiceAccounts);
    }

    /**
     * Show the form for editing the specified BoholWaterServiceAccounts.
     */
    public function edit($id)
    {
        $boholWaterServiceAccounts = $this->boholWaterServiceAccountsRepository->find($id);

        if (empty($boholWaterServiceAccounts)) {
            Flash::error('Bohol Water Service Accounts not found');

            return redirect(route('boholWaterServiceAccounts.index'));
        }

        return view('bohol_water_service_accounts.edit')->with('boholWaterServiceAccounts', $boholWaterServiceAccounts);
    }

    /**
     * Update the specified BoholWaterServiceAccounts in storage.
     */
    public function update($id, UpdateBoholWaterServiceAccountsRequest $request)
    {
        $boholWaterServiceAccounts = $this->boholWaterServiceAccountsRepository->find($id);

        if (empty($boholWaterServiceAccounts)) {
            Flash::error('Bohol Water Service Accounts not found');

            return redirect(route('boholWaterServiceAccounts.index'));
        }

        $boholWaterServiceAccounts = $this->boholWaterServiceAccountsRepository->update($request->all(), $id);

        Flash::success('Bohol Water Service Accounts updated successfully.');

        return redirect(route('boholWaterServiceAccounts.index'));
    }

    /**
     * Remove the specified BoholWaterServiceAccounts from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $boholWaterServiceAccounts = $this->boholWaterServiceAccountsRepository->find($id);

        if (empty($boholWaterServiceAccounts)) {
            Flash::error('Bohol Water Service Accounts not found');

            return redirect(route('boholWaterServiceAccounts.index'));
        }

        $this->boholWaterServiceAccountsRepository->delete($id);

        Flash::success('Bohol Water Service Accounts deleted successfully.');

        return redirect(route('boholWaterServiceAccounts.index'));
    }

    public function uploadFile(Request $request) {
        return view('/bohol_water_service_accounts/upload_file', [

        ]);
    }

    public function validateUploadedFile(Request $request) {
        // Validate the uploaded file
        // $request->validate([
        //     'file' => 'required|mimes:txt|max:10000', // Adjust the file type and size limit as needed
        // ]);

        // Get the uploaded file
        $file = $request->file('file');

        // Process the file line by line
        if ($file->isValid()) {
            $path = $file->path();
            $handle = fopen($path, 'r');

            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    // Process each line as needed
                    // $line variable contains the current line from the file
                    // For example, you can save it to the database, perform some operations, etc.

                    // Example: Output each line
                    // echo $line . "<br>";

                    // Example: Save each line to the database
                    // YourModel::create(['content' => $line]);

                    $data = explode("~", $line);
                    // INSERT OR UPDATE DATA
                    $acct = BoholWaterServiceAccounts::where('AccountNumber', $data[0])
                        ->first();
                    if ($acct != null) {
                        // OLD, UPDATE
                        $acct->PreviousAccountNumber = $data[1];
                        $acct->ConsumerName = utf8_encode($data[2]);
                        $acct->ConnectionType = $data[3];
                        $acct->MeterNumber = $data[4];
                        $acct->TotalBill = floatval(trim($data[5]));
                        $acct->WaterBill = floatval(trim($data[6]));
                        $acct->BillsPenalty = floatval(trim($data[7]));
                        $acct->SalesCharge = floatval(trim($data[8]));
                        $acct->SalesPenalty = floatval(trim($data[9]));
                        $acct->OtherCharges = floatval(trim($data[10]));
                        $acct->save();
                    } else {
                        // NEW, CREATE
                        $acct = new BoholWaterServiceAccounts;
                        $acct->id = IDGenerator::generateIDandRandString();
                        $acct->AccountNumber = $data[0];
                        $acct->PreviousAccountNumber = $data[1];
                        $acct->ConsumerName = utf8_encode($data[2]);
                        $acct->ConnectionType = $data[3];
                        $acct->MeterNumber = $data[4];
                        $acct->TotalBill = floatval(trim($data[5]));
                        $acct->WaterBill = floatval(trim($data[6]));
                        $acct->BillsPenalty = floatval(trim($data[7]));
                        $acct->SalesCharge = floatval(trim($data[8]));
                        $acct->SalesPenalty = floatval(trim($data[9]));
                        $acct->OtherCharges = floatval(trim($data[10]));
                        $acct->save();
                    }
                }

                fclose($handle);
            } else {
                // Handle error opening the file
                return response()->json(['error' => 'Unable to open the file.'], 500);
            }
        } else {
            // Handle invalid file
            return response()->json(['error' => 'Invalid file.'], 400);
        }

        Flash::success('Bohol Water Service Accounts updated!');

        return redirect(route('boholWaterServiceAccounts.index'));
    }
}
