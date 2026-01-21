<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TeachersExport implements FromCollection, WithHeadings, WithStyles
{
    private Collection $rows;

    public function __construct($rows)
    {
        $this->rows = collect($rows);
    }

    public function collection()
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Apellidos',
            'DNI',
            'Correo',
            'Teléfono',
            'Usuario',
            'Contraseña',
            'Dirección',
            'Código postal',
            'Provincia',
            'Población',
            'Iban',
            'Observaciones',
            'Estado',
        ];
    }

    /**
     * 🎨 ESTILOS
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [ // 👈 fila 1 (cabecera)
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => '1976D2'], // azul bonito
                ],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical'   => 'center',
                ],
            ],
        ];
    }
}
