<?php

namespace App\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ExportFailure implements FromCollection
    , ShouldAutoSize
    , WithEvents
    , WithHeadings
    , ShouldQueue
{
    use Exportable;

    private int $row_count;
    protected Collection $failures;

    public function __construct(Collection $failures)
    {
        $getAllFailures = $failures->map(function ($item) {
            return ['row' => $item->row(), 'attribute' => $item->attribute(), 'errors' => $item->errors()];
        })->groupBy('row');

        $errors = [];
        $collection = new Collection();
        foreach ($getAllFailures->toArray() as $row => $error) {
            foreach ($error as $e) {
                $errors[$row][] = str_replace($e['attribute'], trans("validation.attributes.{$e['attribute']}"), $e['errors'][0]);
            }
            $collection->push((object)[$row, implode("\r\n", $errors[$row])]);
        }

        $this->row_count = $collection->count() + 1;
        $this->failures = $collection;

//        \Log::channel('abuse')->error($this->failures);
    }

    public function collection(): Collection
    {
        return $this->failures;
    }

    public function registerEvents(): array
    {
        $rowCount = $this->row_count;
//        Log::channel('abuse')->info($rowCount);
        return [
            AfterSheet::class => function (AfterSheet $event) use($rowCount) {
                $event->sheet->getDelegate()->setRightToLeft(true)
                    ->getStyle("A1:B{$rowCount}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
                $event->sheet->styleCells(
                    "A1:B1",
                    [
                        'font' => [
                            'bold' => true,
                            'color' => ['rgb' => 'ffffff']
                        ]
                    ]
                );
                $event->sheet->getDelegate()->getStyle('A1:B1')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('f44336');
            }
        ];
    }

    public function headings(): array
    {
        return [
            'row',
            'errors'
        ];
    }
}
