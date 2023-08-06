<?php

namespace App\Imports;

use App\Models\Company;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CompanyImport implements ToModel, WithStartRow, WithHeadingRow, SkipsEmptyRows, WithBatchInserts, WithChunkReading
{
    public function model(array $row)
    {
        return new Company([
            'account_number' => $row['kreditoren_nr'],
            'name' => $row['name'],
            'uid' => $row['mwst_nr_clean'],
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
