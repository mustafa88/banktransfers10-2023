<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\bank\Enterprise;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class ReportExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        Log::info('report: Aiser');
        return $this->collection;
    }

    protected $collection;

    public function __construct($collection)
    {
        $this->collection = $collection;
    }

    public function headings(): array
    {
        return ['עמותה','מס שורות','חובה','זכות','נטו'];
    }

}
