<?php
namespace App\Exports;

use App\Models\Income;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class IncomeExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Income::with(['sourceUser', 'createdByUser', 'updatedByUser']);
    }

    public function map($income): array
    {
        return [
            $income->title,
            $income->amount,
            optional($income->sourceUser)->name,
            $income->date,
            $income->status,
            optional($income->createdByUser)->name,
            optional($income->updatedByUser)->name,
            $income->created_at,
            $income->updated_at,
        ];
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
