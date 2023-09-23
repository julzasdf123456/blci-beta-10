<?php

namespace App\Models;


class RateUploadHelper
{
    public $area;
    public $districtName;
    public $startingCell;

    public function __construct($area, $districtName, $startingCell) {
        $this->area = $area;
        $this->districtName = $districtName;
        $this->startingCell = $startingCell;
    }
}