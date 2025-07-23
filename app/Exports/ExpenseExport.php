<?php

namespace App\Exports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExpenseExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Expense::with('createdByUser', 'updatedByUser')
            ->get()
            ->map(function ($expense) {
                return [
                    'Title'       => $expense->title,
                    'Amount'      => $expense->amount,
                    'Description' => $expense->description,
                    'Date'        => $expense->date,
                    'Created By'  => optional($expense->createdByUser)->name,
                    'Updated By'  => optional($expense->updatedByUser)->name,
                    'Created At'  => $expense->created_at,
                    'Updated At'  => $expense->updated_at,
                ];
            });
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
