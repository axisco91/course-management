<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TrainingContractBillsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected Collection $rows;

    public function __construct(Collection $rows)
    {
        $this->rows = $rows;
    }

    public function collection()
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [
            'Nº Fac',
            'Nº CFA',
            'Alumno',
            'Mes',
            'Año',
            'Empresa',
            'Facturado',
            'Cobrado',
        ];
    }

    private function monthName($m): string
    {
        $map = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
        ];

        $n = (int) $m;

        return $map[$n] ?? (string) $m;
    }

    private function yesNo($v): string
    {
        return ((string) $v === '1' || $v === 1 || $v === true) ? 'Sí' : 'No';
    }

    public function map($row): array
    {
        // Ajusta keys a tu Resource / query real:
        $billNumber = $row->bill_number ?? $row->billing_number ?? $row->invoice_number ?? $row->number ?? '';
        $cfaNumber  = $row->cfa ?? $row->cfa_number ?? $row->number_cfa ?? '';

        $student = $row->student_name
            ?? $row->student
            ?? trim(($row->student_name_db ?? '') . ' ' . ($row->student_surname_db ?? ''));

        // si viene relación
        if (!$student && isset($row->student) && is_object($row->student)) {
            $student = trim(($row->student->name ?? '') . ' ' . ($row->student->surname ?? ''));
        }

        $company = $row->company_name ?? $row->company ?? '';
        if (!$company && isset($row->company) && is_object($row->company)) {
            $company = $row->company->name ?? '';
        }

        $month = $row->month_name ?? $this->monthName($row->month ?? null);

        return [
            $billNumber,
            $cfaNumber,
            $student,
            $month,
            $row->year ?? '',
            $company,
            $this->yesNo($row->invoiced ?? 0),
            $this->yesNo($row->charged ?? 0),
        ];
    }
}
