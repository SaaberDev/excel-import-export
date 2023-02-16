<?php

namespace App\Http\Controllers\LaravelExcel;

use App\Http\Controllers\Controller;
use App\Imports\ImportUser;
use App\Jobs\ExportJob;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    protected array $logs = [
        'errors' => [],
    ];

    public function index()
    {
//        $importLogs = ImportLog::query()->latest()->first();
//        $data = [];
//        if ($importLogs) {
//            $error_logs = json_decode($importLogs->error_log);
//            if (empty($error_logs)) {
//                $data = [];
//            } else {
//                foreach ($error_logs as $index => $error) {
//                    $data[] = [
//                        'id' => $index + 1,
//                        'row' => $error->row,
//                        'column' => $error->column,
//                        'message' => $error->message,
//                        'value' => $error->value,
//                    ];
//                }
//            }
//        }
//
//        $totalRecords = $importLogs->total_records ?? '';
//        $totalPassed = $importLogs->total_success ?? '';
//        $totalFailure = $importLogs->total_errors ?? '';
//        $importedBy = $importLogs->imported_by ?? '';
//        $importedTo = $importLogs ? explode("\\", $importLogs->imported_to)[2] . ' Table' : '';
//        $importedAt = $importLogs ? Carbon::parse($importLogs->created_at)->diffForHumans() : '';
//
//        $data = collect($data);
//        $errors = $this->paginateCollection($data);

        return view('guest.import.index',
//            compact(
//                'errors',
//                'totalRecords',
//                'totalPassed',
//                'totalFailure',
//                'importedBy',
//                'importedTo',
//                'importedAt',
//            )
        );
    }

    public function import(Request $request)
    {
        $file = $request->file('file');
        $importInstance = (new ImportUser());
        Excel::import($importInstance, $file);
        return redirect()->back();
    }

    public function seeBatch($id)
    {
        return \Bus::findBatch($id)->toArray();
    }

    /**
     * @throws \Throwable
     */
    public function export()
    {
        $batch = \Bus::batch([
            new ExportJob()
        ])->dispatch();

        return redirect()->route('seeBatch', $batch->id);
    }
}
