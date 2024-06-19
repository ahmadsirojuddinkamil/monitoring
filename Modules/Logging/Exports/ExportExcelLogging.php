<?php

namespace Modules\Logging\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class ExportExcelLogging implements FromCollection
{
    protected $logData;

    public function __construct(array $logData)
    {
        $this->logData = $logData;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $formattedLogData = [];
        foreach ($this->logData as $type => $logs) {
            foreach ($logs as $log) {
                $formattedLogData[] = [$type, $log];
            }
        }

        return collect($formattedLogData);
    }
}
