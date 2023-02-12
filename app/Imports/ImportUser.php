<?php

namespace App\Imports;

use App\Abstracts\ToCollectionImport;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportUser extends ToCollectionImport implements SkipsOnError
    , SkipsOnFailure
    , SkipsEmptyRows
    , WithValidation
    , WithStartRow
    , WithHeadingRow
    , WithChunkReading
    , WithBatchInserts
    , ShouldQueue
{
    use Importable, SkipsErrors, SkipsFailures;

    public function __construct()
    {
        $path = \Storage::path(config('application_settings.excel.logs.path') . config('application_settings.excel.logs.file_name'));
        $file = fopen($path, 'w');
        fclose($file);
    }

    /**
     * skip heading row and start next row.
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * @return string[][]
     */
    public function rules(): array
    {
        return [
            '*.name' => ['required'],
            '*.email' => ['email', 'unique:users,email'],
        ];
    }

    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 5;
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 5;
    }

    /**
     * @param Collection $rows
     * @return User
     */
    public function processImport(Collection $rows): User
    {
        foreach ($rows as $row) {
            User::query()->insert([
                'name' => $row['name'],
                'email' => $row['email'],
                'email_verified_at' => $row['email_verified_at'] ?? null,
                'password' => \Hash::make('secret'),
                'remember_token' => $row['remember_token'] ?? null,
            ]);
        }
    }

    public function getUser()
    {
        return 1;
    }
}

