<?php

namespace App\Exports;

ini_set('max_execution_time', '900');

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\SalesConsolidatedSheets;

class SalesConsolidatedExport implements WithMultipleSheets {
    use Exportable;

    private $period, $sales;

    public function __construct($period, $sales) {
        $this->period = $period;
        $this->sales = $sales;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        $sheets[0] = new SalesConsolidatedSheets($this->period, '01', 'Cadiz City', $this->sales);
        $sheets[1] = new SalesConsolidatedSheets($this->period, '02', 'EB Magalona', $this->sales);
        $sheets[2] = new SalesConsolidatedSheets($this->period, '03', 'Manapla', $this->sales);
        $sheets[3] = new SalesConsolidatedSheets($this->period, '04', 'Victorias City', $this->sales);
        $sheets[4] = new SalesConsolidatedSheets($this->period, '05', 'San Carlos City', $this->sales);
        $sheets[5] = new SalesConsolidatedSheets($this->period, '06', 'Sagay City', $this->sales);
        $sheets[6] = new SalesConsolidatedSheets($this->period, '07', 'Escalante City', $this->sales);
        $sheets[7] = new SalesConsolidatedSheets($this->period, '08', 'Calatrava', $this->sales);
        $sheets[8] = new SalesConsolidatedSheets($this->period, '09', 'Toboso', $this->sales);
        $sheets[9] = new SalesConsolidatedSheets($this->period, '00', 'Consolidated', $this->sales);

        return $sheets;
    }
}