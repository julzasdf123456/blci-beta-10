<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateWarehouseItemsRequest;
use App\Http\Requests\UpdateWarehouseItemsRequest;
use App\Repositories\WarehouseItemsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\WarehouseItems;
use Flash;
use Response;

class WarehouseItemsController extends AppBaseController
{
    /** @var  WarehouseItemsRepository */
    private $warehouseItemsRepository;

    public function __construct(WarehouseItemsRepository $warehouseItemsRepo)
    {
        $this->middleware('auth');
        $this->warehouseItemsRepository = $warehouseItemsRepo;
    }

    /**
     * Display a listing of the WarehouseItems.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $warehouseItems = $this->warehouseItemsRepository->all();

        return view('warehouse_items.index')
            ->with('warehouseItems', $warehouseItems);
    }

    /**
     * Show the form for creating a new WarehouseItems.
     *
     * @return Response
     */
    public function create()
    {
        return view('warehouse_items.create');
    }

    /**
     * Store a newly created WarehouseItems in storage.
     *
     * @param CreateWarehouseItemsRequest $request
     *
     * @return Response
     */
    public function store(CreateWarehouseItemsRequest $request)
    {
        $input = $request->all();

        $warehouseItems = $this->warehouseItemsRepository->create($input);

        Flash::success('Warehouse Items saved successfully.');

        return redirect(route('warehouseItems.index'));
    }

    /**
     * Display the specified WarehouseItems.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $warehouseItems = $this->warehouseItemsRepository->find($id);

        if (empty($warehouseItems)) {
            Flash::error('Warehouse Items not found');

            return redirect(route('warehouseItems.index'));
        }

        return view('warehouse_items.show')->with('warehouseItems', $warehouseItems);
    }

    /**
     * Show the form for editing the specified WarehouseItems.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $warehouseItems = $this->warehouseItemsRepository->find($id);

        if (empty($warehouseItems)) {
            Flash::error('Warehouse Items not found');

            return redirect(route('warehouseItems.index'));
        }

        return view('warehouse_items.edit')->with('warehouseItems', $warehouseItems);
    }

    /**
     * Update the specified WarehouseItems in storage.
     *
     * @param int $id
     * @param UpdateWarehouseItemsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateWarehouseItemsRequest $request)
    {
        $warehouseItems = $this->warehouseItemsRepository->find($id);

        if (empty($warehouseItems)) {
            Flash::error('Warehouse Items not found');

            return redirect(route('warehouseItems.index'));
        }

        $warehouseItems = $this->warehouseItemsRepository->update($request->all(), $id);

        Flash::success('Warehouse Items updated successfully.');

        return redirect(route('warehouseItems.index'));
    }

    /**
     * Remove the specified WarehouseItems from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $warehouseItems = $this->warehouseItemsRepository->find($id);

        if (empty($warehouseItems)) {
            Flash::error('Warehouse Items not found');

            return redirect(route('warehouseItems.index'));
        }

        $this->warehouseItemsRepository->delete($id);

        Flash::success('Warehouse Items deleted successfully.');

        return redirect(route('warehouseItems.index'));
    }

    public function getSearchedMaterials(Request $request) {
        $regex = $request['Regex'];

        $data = DB::connection('mysql')
                ->select(
                    "Select 
                        a.it_code as itcode, 
                        b.itm_desc as itdesc,
                        a.uom as uom, 
                        a.cst as cst, 
                        (a.cst * 1.20) as sprice, 
                        (a.cst * 1.12) as dprice,
                        (SUM(c.lgr_qtyin) - SUM(c.lgr_qtyout)) as qty, 
                        a.id as itmno 
                    FROM tblitems_cost a 
                    INNER JOIN tblitems b ON a.it_code = b.itm_code 
                    INNER JOIN tblitem_lgr c ON a.it_code = c.lgr_itmcode AND a.cst = c.lgr_cost and c.status = 'POSTED' 
                    WHERE (itm_desc LIKE '%" . $regex . "%' OR it_code LIKE '%" . $regex . "%')
                    GROUP BY itmno 
                    ORDER BY itdesc, rdate DESC"
                );

        $output = '';
        foreach($data as $item) {
            $output .= "<tr onclick=selectMaterialItem('" . $item->itmno . "')
                                id='" . $item->itmno . "' 
                                data_itcode='" . $item->itcode . "'
                                data_itdesc='" . $item->itdesc . "'
                                data_uom='" . $item->uom . "'
                                data_cst='" . $item->sprice . "' 
                                data_unitprice='" . $item->cst . "'>
                            <td>" . $item->itcode . "</td>
                            <td>" . $item->itdesc . "</td>
                            <td>" . $item->uom . "</td>
                            <td class='text-right'>" . (is_numeric($item->cst) ? number_format(floatval($item->cst), 2) : $item->cst) . "</td>
                            <td class='text-right'>" . (is_numeric($item->sprice) ? number_format(floatval($item->sprice), 2) : $item->sprice) . "</td>
                            <td class='text-right'>" . (is_numeric($item->dprice) ? number_format(floatval($item->dprice), 2) : $item->dprice) . "</td>
                            <td class='text-right'>" . $item->qty . "</td>
                        </tr>";
        }

        return response()->json($output, 200);
    }

    public function getSearchedMeters(Request $request) {
        $regex = $request['Regex'];

        $data = DB::connection('mysql')
                ->select(
                    "Select 
                        a.it_code as itcode, 
                        b.itm_desc as itdesc,
                        a.uom as uom, 
                        a.cst as cst, 
                        (a.cst * 1.20) as sprice, 
                        (a.cst * 1.12) as dprice,
                        (SUM(c.lgr_qtyin) - SUM(c.lgr_qtyout)) as qty, 
                        a.id as itmno 
                    FROM tblitems_cost a 
                    INNER JOIN tblitems b ON a.it_code = b.itm_code 
                    INNER JOIN tblitem_lgr c ON a.it_code = c.lgr_itmcode AND a.cst = c.lgr_cost and c.status = 'POSTED' 
                    WHERE (itm_desc LIKE '%" . $regex . "%' OR it_code LIKE '%" . $regex . "%')
                    GROUP BY itmno 
                    ORDER BY itdesc, rdate DESC"
                );

        $output = '';
        foreach($data as $item) {
            $output .= "<tr onclick=selectMaterial('" . $item->itmno . "')
                                id='" . $item->itmno . "' 
                                meter_data_itcode='" . $item->itcode . "'
                                meter_data_itdesc='" . $item->itdesc . "'
                                meter_data_uom='" . $item->uom . "'
                                meter_data_cst='" . $item->cst . "'
                                data_unitprice='" . $item->cst . "'>
                            <td>" . $item->itcode . "</td>
                            <td>" . $item->itdesc . "</td>
                            <td>" . $item->uom . "</td>
                            <td class='text-right'>" . (is_numeric($item->cst) ? number_format(floatval($item->cst), 2) : $item->cst) . "</td>
                            <td class='text-right'>" . (is_numeric($item->sprice) ? number_format(floatval($item->sprice), 2) : $item->sprice) . "</td>
                            <td class='text-right'>" . (is_numeric($item->dprice) ? number_format(floatval($item->dprice), 2) : $item->dprice) . "</td>
                            <td class='text-right'>" . $item->qty . "</td>
                        </tr>";
        }

        return response()->json($output, 200);
    }

    public function removeItem(Request $request) {
        $id = $request['id'];

        $item = WarehouseItems::find($id);
        if ($item->id != null) {
            $item->delete();
        }

        return response()->json('ok', 200);
    }
}
