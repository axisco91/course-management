<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsExport implements FromCollection, WithHeadings
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
            'Empresa',
            'Usuario',
            'Contraseña',
            'Fecha Nacimiento',
            'Nivel de Estudio',
            'Descapacitado',
            'Nº Seguridad Social',
            'C. Cotización',
            'Grupo Cotización',
            'Categoría Profesional',
            'Salario Bruto Anual',
            'Horas Anuales',
            'Coste Hora Bruto del Trabajador',
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
