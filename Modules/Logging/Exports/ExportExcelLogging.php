<?php

namespace Modules\Logging\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class ExportExcelLogging implements FromArray
{
    protected $logData;

    public function __construct(array $logData)
    {
        $this->logData = $logData;
    }

    public function array(): array
    {
        $exportData = [];

        foreach ($this->logData as $log) {
            $exportData[] = [$log];
        }

        return $exportData;
    }
}
