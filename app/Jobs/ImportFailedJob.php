<?php

namespace App\Jobs;

use App\Models\ImportLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportFailedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $model;
    public $totalRow;
    public $importCounter;
    public $failCounter;
    public $logs;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($model, $totalRow, $importCounter, $failCounter, $logs)
    {
        $this->model = $model;
        $this->totalRow = $totalRow;
        $this->importCounter = $importCounter;
        $this->failCounter = $failCounter;
        $this->logs = $logs;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ImportLog::query()->create([
            'imported_to' => $this->model,
            'imported_by' => 1, // auth user_id
            'error_log' => json_encode($this->logs['errors']),
            'total_records' => $this->totalRow,
            'total_success' => $this->importCounter,
            'total_errors' => $this->failCounter,
        ]);
    }
}
