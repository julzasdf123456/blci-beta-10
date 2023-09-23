<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\SummaryOfSalesPerAreaExport;
use App\Exports\SummaryOfSalesERCExport;

class SummaryOfSalesExport implements WithMultipleSheets {
    use Exportable;

    private $data, $dataErc, $period, $sales, $demandTotal;

    public function __construct(array $data, array $dataErc, $period, $sales, $demandTotal) {
        $this->data = $data;
        $this->dataErc = $dataErc;
        $this->period = $period;
        $this->sales = $sales;
        $this->demandTotal = $demandTotal;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        $sheets[0] = new SummaryOfSalesPerAreaExport($this->data, $this->period, $this->sales, $this->demandTotal);
        $sheets[1] = new SummaryOfSalesERCExport($this->dataErc, $this->period, $this->sales, $this->demandTotal);

        return $sheets;
    }
}