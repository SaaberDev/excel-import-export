<?php

namespace App\Imports;

use App\Jobs\ImportFailedJob;
use App\Jobs\ImportUserJob;
use App\Jobs\ReadExcelJob;
use App\Models\ImportLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\ImportFailed;
use Maatwebsite\Excel\Jobs\AfterImportJob;
use Maatwebsite\Excel\Validators\Failure;
use Validator;

class ImportUser implements
    ToCollection,
    WithHeadingRow,
    WithStartRow,
    SkipsOnFailure,
//    SkipsOnError,
    WithValidation,
//    WithEvents,
    WithChunkReading,
    ShouldQueue
//    WithBatchInserts
{
    use Importable;

    private $model;
    private int $importCounter = 0;
    private int $failCounter = 0;
    protected array $logs = [
        'errors' => [],
    ];
    private array $rules;

    public function __construct(array $rules, $model)
    {
        $this->model = $model;
        $this->rules = $rules;
    }

    public function headingRow()
    {
        return 1;
    }

    public function startRow(): int
    {
        return 2;
    }

    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
//        dd($rows);
//        $chunkedRows = $rows->chunk(500);
        foreach ($rows as $row) {
//            ImportUserJob::dispatch($row, $this->importCounter, $this->model);
            foreach ($row as $item) {
                ++$this->importCounter;
//                ImportUserJob::dispatch($this->model, $item);
                $this->model::query()->insert($item->toArray());
            }
        }

        $this->generateReport();
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getLogs()
    {
        return $this->logs['errors'];
    }

    public function getImportCounter(): int
    {
        return $this->importCounter ?? 0;
    }

    public function getFailCounter(): int
    {
        return $this->failCounter ?? 0;
    }

    public function getTotalRow(): int
    {
        return $this->importCounter + $this->failCounter;
    }

    public function onFailure(Failure ...$failures)
    {
//        $chunkedFailures = array_chunk($failures, 2000);
        if (count($failures) > 0) {
            foreach ($failures as $failure) {
//                foreach ($chunks as $failure) {
                    ++$this->failCounter;
                    $this->logs['errors'][] = [
                        'row' => $failure->row(),
                        'column' => $failure->attribute(),
                        'message' => implode($failure->errors()),
                        'value' => $failure->values()[$failure->attribute()]
                    ];
//                }
            }
        }

        $this->generateReport();

    }

    private function generateReport()
    {
        ImportLog::query()->create([
            'imported_to' => $this->model,
            'imported_by' => 1, // auth user_id
            'error_log' => json_encode($this->logs['errors']),
            'total_records' => $this->getTotalRow(),
            'total_success' => $this->getImportCounter(),
            'total_errors' => $this->getFailCounter()
        ]);
    }


    public function rules(): array
    {
        return $this->rules;
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Required',
            'email.required' => 'Required',
            'email.unique' => 'Skipped, Duplicate entry found on :attribute'
        ];
    }

//    public function registerEvents(): array
//    {
//        return [
////            AfterSheet::class => [self::class, 'generateReport'],
//            AfterImport::class => function(AfterImport $afterImport) {
////            dd(array_);
//                // Store error log after import
//
////                ImportFailedJob::dispatch($this->model, $this->getTotalRow(), $this->getImportCounter(), $this->getFailCounter(), $this->logs);
//            }
//        ];
//    }

    public function chunkSize(): int
    {
        return 5;
    }

//    public function batchSize(): int
//    {
//        return 500;
//    }
}
