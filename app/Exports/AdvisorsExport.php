<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;

class AdvisorsExport implements FromArray, WithHeadings, WithStyles
{
    private array $rows;

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function headings(): array
    {
        // Usa el orden EXACTO del excel plantilla
        return [
            'Nombre',
            'CIF',
            'Tipo',
            'Actividad',
            'Email',
            'Teléfono',
            'Representante Legal',
            'DNI Representante Legal',
            'IRPF',
            'Comisión',
            'Contacto 1',
            'Contacto 2',
            'Contacto 3',
            'Cotización',
            'Colaborador',
            'CNAE',
            'Plantilla Media',
            'IBAN',
            'SEPA',
            'B2B',
            'Dirección',
            'Código Postal',
            'Provincia',
            'Población',
            'Asesor',
            'Potencial',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Estilo cabecera (fila 1)
        $sheet->getStyle('A1:Z1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => Color::COLOR_WHITE],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => '1F4E79'], // azul oscuro
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(22);

        // Auto-ajuste simple
        foreach (range('A', 'Z') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [];
    }
}
