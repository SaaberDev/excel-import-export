<?php

namespace App\Http\Controllers;

use App\Exports\ExportFailure;
use App\Exports\ExportUser;
use App\Imports\ImportUser;
use App\Imports\ImportUserNew;
use App\Jobs\ReadExcelJob;
use App\Models\ImportLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class ImportController extends Controller
{
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
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:users'
        ];
        $importInstance = (new ImportUser($rules, User::class));
        Excel::import($importInstance, $file);
        return redirect()->back();
    }

    public function map()
    {
        //
    }

    public function export()
    {
        return Excel::download(new ExportUser(), 'users.csv');
//        return Excel::download(new ExportFailure(), 'users.csv');
    }

    public function importNew(Request $request)
    {
        $file = $request->file('file');
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:users'
        ];
        $importInstance = (new ImportUserNew());
        Excel::import($importInstance, $file);
        return redirect()->back();
    }
}
