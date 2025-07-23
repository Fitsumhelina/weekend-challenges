<?php

namespace App\Exports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExpenseExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Expense::with('createdByUser', 'updatedByUser');
    }

    public function map($expense): array
    {
        return [
            $expense->title,
            $expense->amount,
            $expense->description,
            $expense->date,
            optional($expense->createdByUser)->name,
            optional($expense->updatedByUser)->name,
            $expense->created_at,
            $expense->updated_at,
        ];
    }

    public function headings(): array
    {
        return [
            'Title',
            'Amount',
            'Description',
            'Date',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At',
        ];
    }
}
