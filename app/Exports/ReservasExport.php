<?php

namespace App\Exports;

use App\Models\ReservaCal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;


class ReservasExport implements FromCollection, WithHeadings, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function collection()
    {
        return ReservaCal::all([
            'salon', 'fecha_inicio', 'fecha_final', 'hora_inicio', 'hora_fin', 'actividad', 'analista', 'estatus', 'cant_participantes', 'depto_responsable', 'numero_evento', 'scafid', 'mes', 'tipo_actividad', 'subtipo_actividad', 'publico_meta', 'insumos', 'montaje', 
        ]);
    }

    public function headings(): array
    {
        return [
            'Salón', 'Fecha Inicio', 'Fecha Final', 'Hora Inicio', 'Hora Fin', 'Actividad', 'Analista', 'Estatus', 'Participantes', 'Depto.', 'N° Evento', 'Scafid', 'Mes', 'Tipo', 'Subtipo', 'Público Meta', 'Insumos', 'Montaje', 
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $colores = [
            'Auditorio Jorge L. Quijada' => 'FF800080', // Morado
            'Trabajo en Equipo' => 'FF198754', // Verde Bootstrap
            'Comunicación Asertiva' => 'FF0dcaf0', // Celeste Bootstrap
            'Servicio al Cliente' => 'FFffc107', // Amarillo Bootstrap
            'Integridad' => 'FFdc3545', // Rojo Bootstrap
            'Creatividad Innovadora' => 'FF0d6efd', // Azul Bootstrap
            'Externo' => 'FF212529', // Negro Bootstrap
        ];

        $rows = ReservaCal::count();

        //cambiar ancho de columnas (Ejemplo: A=20, B=15, C=15, etc.)
        $sheet->getColumnDimension('A')->setWidth(20); // Salón
        $sheet->getColumnDimension('B')->setWidth(10); // Fecha Inicio
        $sheet->getColumnDimension('C')->setWidth(10); // Fecha Final
        $sheet->getColumnDimension('D')->setWidth(10); // Hora Inicio
        $sheet->getColumnDimension('E')->setWidth(10); // Hora Fin
        $sheet->getColumnDimension('F')->setWidth(15); // Actividad
        $sheet->getColumnDimension('G')->setWidth(15); // Analista
        $sheet->getColumnDimension('H')->setWidth(15); // Estatus
        $sheet->getColumnDimension('I')->setWidth(12); // Participantes
        $sheet->getColumnDimension('J')->setWidth(10); // Departamento
        $sheet->getColumnDimension('K')->setWidth(10); // Numero Evento
        $sheet->getColumnDimension('L')->setWidth(10); // Scafid
        $sheet->getColumnDimension('M')->setWidth(11); // Mes
        $sheet->getColumnDimension('N')->setWidth(15); // Tipo
        $sheet->getColumnDimension('O')->setWidth(12); // Subtipo
        $sheet->getColumnDimension('P')->setWidth(13); // Publico Meta
        $sheet->getColumnDimension('Q')->setWidth(15); // Requisitos
        $sheet->getColumnDimension('R')->setWidth(15); // Insumos
        $sheet->getColumnDimension('S')->setWidth(15); // Montaje
        $sheet->getColumnDimension('T')->setWidth(15); // Asistencia

        // Cambiar altura de filas (ejemplo: todas las filas a 25)
        for ($row = 1; $row <= $rows + 1; $row++) {
            $sheet->getRowDimension($row)->setRowHeight(30);
        }

        // Ajustar texto: encabezados en negrita y centrados
        $sheet->getStyle('A1:T1')->getFont()->setBold(true);
        $sheet->getStyle('A1:T1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Ajustar texto: toda la hoja alineado verticalmente al centro y ajuste de texto automático
        $sheet->getStyle('A1:T' . ($rows + 1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:T' . ($rows + 1))->getAlignment()->setWrapText(true);

        // Colorear la columna salon
        $rows = ReservaCal::count();
        for ($row = 2; $row <= $rows + 1; $row++) {
            $salon = $sheet->getCell('A' . $row)->getValue();
            if (isset($colores[$salon])) {
                $sheet->getStyle('A' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($colores[$salon]);
                $sheet->getStyle('A' . $row)->getFont()->getColor()->setARGB('FFFFFFFF'); // Texto blanco
                $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            }
        }
        return [];
    }
    
}
