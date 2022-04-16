<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ExportUser implements FromQuery, WithHeadings, WithMapping
{
    public $users;

    public function __construct()
    {
        $this->users = new User();
    }

    /**
    * @return User
     */
    public function query()
    {
        $this->users->makeVisible(['password', 'remember_token']);
        return $this->users;
    }

    public function headings(): array
    {
        return \Schema::getColumnListing($this->users->getTable());
    }

    public function map($row): array
    {
        $columns = \Schema::getColumnListing($this->users->getTable());
        $columns = array_slice($columns, 0, -2);

        $map = [];
        foreach ($columns as $column) {
            $map[] = $row->$column;
        }
        if ($row->created_at) {
            $map[] = Date::dateTimeFromTimestamp($row->created_at);
        }
        if ($row->updated_at) {
            $map[] = Date::dateTimeFromTimestamp($row->updated_at);
        }

        return $map;

    }
}
