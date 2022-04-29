<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ExportUser implements FromQuery
    , WithHeadings
    , WithMapping
{
    use Exportable;

    /**
    * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return User::query();
    }

    public function headings(): array
    {
        return [
            'name',
            'email',
            'created_at',
//            'updated_at',
        ];
//        return \Schema::getColumnListing($this->users->getTable());
    }

    public function map($row): array
    {
        return [
            'name' => $row->name,
            'email' => $row->email,
            'created_at' => Date::dateTimeFromTimestamp($row->created_at),
//            'updated_at' => Date::dateTimeFromTimestamp($row->updated_at)
        ];
    }

    public function fields(): array
    {
        return [
            'name',
            'email',
            'created_at',
//            'created_at'
        ];
    }
}
