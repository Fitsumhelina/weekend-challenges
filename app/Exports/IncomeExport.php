<?php
namespace App\Exports;

use App\Models\Income;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class IncomeExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Income::with('sourceUser')
            ->get()
            ->map(function ($income) {
                return [
                    'Title' => $income->title,
                    'Amount' => $income->amount,
                    'Source' => optional($income->sourceUser)->name,
                    'Date' => $income->date,
                    'Status' => $income->status,
                    'Created By' => optional($income->createdByUser)->name,
                    'Updated By' => optional($income->updatedByUser)->name,
                    'Created At' => $income->created_at,
                    'Updated At' => $income->updated_at,
                ];
            });
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
