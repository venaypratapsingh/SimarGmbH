<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeeExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Employee::with('schedules')->get();
    }

    /**
     * Define the headings for the export
     */
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Member Since',
            'Schedule',
            'Created At',
            'Updated At'
        ];
    }

    /**
     * Map the employee data for export
     */
    public function map($employee): array
    {
        return [
            $employee->id,
            $employee->name,
            $employee->member_since,
            $employee->schedules->first() ? $employee->schedules->first()->slug : 'No Schedule',
            $employee->created_at,
            $employee->updated_at
        ];
    }
}
