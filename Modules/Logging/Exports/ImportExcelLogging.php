<?php

namespace Modules\Logging\Exports;

use Maatwebsite\Excel\Concerns\ToArray;

class ImportExcelLogging implements ToArray
{
    public $rowCount = 0;

    public $allData = [];

    public function array(array $array)
    {
        $this->rowCount = count($array);
        $this->allData = array_slice($array, 0, 5);
    }
}
