<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class ArrayExport implements FromArray
{
    public function __construct(private array $rows) {}

    public function array(): array
    {
        if (empty($this->rows)) return [];

        $headers = array_keys($this->rows[0]);
        $data = [$headers];

        foreach ($this->rows as $r) {
            $data[] = array_values($r);
        }

        return $data;
    }
}