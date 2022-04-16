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

class ImportUserNew extends ToCollectionImport implements SkipsOnError
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
            '*.email_verified_at' => ['required'],
            '*.password' => ['required'],
            '*.remember_token' => ['required'],
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
//        $role = Role::findByName('normal-user');
        foreach ($rows as $row) {
            User::create([
                'name' => $row['name'],
                'email' => $row['email'],
                'email_verified_at' => $row['email_verified_at'],
                'password' => $row['password'],
                'remember_token' => $row['remember_token'],
            ]);
//            if ($user) {
//                //Todo: set organization from session.
//                //Todo: set group user.
//                $user->assignRole([$role->id => ['organization_id' => Organization::DEFAULT]]);
//            }
        }
    }

    public function getUser()
    {
        return 1;
    }
}

