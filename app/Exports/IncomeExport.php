<?php
namespace App\Exports;

use App\Models\Income;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class IncomeExport implements FromCollection, WithHeadings
{
    public function collection()
    {
         return Income::all();
    }

    public function headings(): array
    {
        return [
            'Title',
            'Amount',
            'Source',
            'Date',
            'Status',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At',
        ];
    }

}
