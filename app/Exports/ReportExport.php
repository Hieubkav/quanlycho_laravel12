<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReportExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    public function __construct(protected Report $report)
    {
    }

    public function collection()
    {
        return $this->report->reportItems()
            ->with(['survey.market', 'survey.sale', 'product.unit'])
            ->orderBy('survey_id')
            ->orderBy('product_id')
            ->get();
    }

    public function headings(): array
    {
        return [
            'STT',
            'Ngày khảo sát',
            'Chợ',
            'Nhân viên',
            'Sản phẩm',
            'Đơn vị',
            'Giá trung bình (VNĐ)',
            'Ghi chú',
        ];
    }

    public function map($reportItem): array
    {
        static $counter = 0;
        $counter++;

        return [
            $counter,
            $reportItem->survey?->survey_day?->format('d/m/Y') ?? '-',
            $reportItem->survey?->market?->name ?? '-',
            $reportItem->survey?->sale?->name ?? '-',
            $reportItem->product?->name ?? '-',
            $reportItem->product?->unit?->name ?? '-',
            $reportItem->price ? (float) $reportItem->price : 0,
            $reportItem->notes ?? '',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                // Insert title and info rows at the top
                $sheet->insertNewRowBefore(1, 3);

                // Add report title
                $sheet->setCellValue('A1', 'BÁO CÁO KHẢO SÁT GIÁ');
                $sheet->mergeCells('A1:H1');

                // Add date range
                $fromDay = $this->report->from_day->format('d/m/Y');
                $toDay = $this->report->to_day->format('d/m/Y');
                $sheet->setCellValue('A2', "Từ ngày: {$fromDay} - Đến ngày: {$toDay}");
                $sheet->mergeCells('A2:H2');

                // Style title (Row 1)
                $sheet->getStyle('A1:H1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['argb' => 'FFFFFFFF'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF2563EB'],
                    ],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(30);

                // Style date range (Row 2)
                $sheet->getStyle('A2:H2')->applyFromArray([
                    'font' => [
                        'italic' => true,
                        'size' => 11,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFE0E7FF'],
                    ],
                ]);
                $sheet->getRowDimension(2)->setRowHeight(20);

                // Style header row (Row 4)
                $sheet->getStyle('A4:H4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'color' => ['argb' => 'FFFFFFFF'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF059669'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);
                $sheet->getRowDimension(4)->setRowHeight(25);

                // Style data rows (from row 5 onwards)
                $dataStartRow = 5;
                $dataEndRow = $highestRow + 3; // +3 because we inserted 3 rows

                if ($dataEndRow >= $dataStartRow) {
                    // Apply borders to all data cells
                    $sheet->getStyle("A{$dataStartRow}:H{$dataEndRow}")->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => 'FFCCCCCC'],
                            ],
                        ],
                    ]);

                    // Center align specific columns
                    $sheet->getStyle("A{$dataStartRow}:A{$dataEndRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // STT
                    $sheet->getStyle("B{$dataStartRow}:B{$dataEndRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Date
                    $sheet->getStyle("F{$dataStartRow}:F{$dataEndRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Unit

                    // Right align price column
                    $sheet->getStyle("G{$dataStartRow}:G{$dataEndRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                    // Format price column as number with thousand separators
                    $sheet->getStyle("G{$dataStartRow}:G{$dataEndRow}")->getNumberFormat()->setFormatCode('#,##0');

                    // Apply alternating row colors for better readability
                    for ($row = $dataStartRow; $row <= $dataEndRow; $row++) {
                        if (($row - $dataStartRow) % 2 === 1) {
                            $sheet->getStyle("A{$row}:H{$row}")->applyFromArray([
                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => ['argb' => 'FFF9FAFB'],
                                ],
                            ]);
                        }
                    }
                }

                // Set column widths
                $sheet->getColumnDimension('A')->setWidth(8);  // STT
                $sheet->getColumnDimension('B')->setWidth(15); // Date
                $sheet->getColumnDimension('C')->setWidth(25); // Market
                $sheet->getColumnDimension('D')->setWidth(20); // Sale
                $sheet->getColumnDimension('E')->setWidth(30); // Product
                $sheet->getColumnDimension('F')->setWidth(12); // Unit
                $sheet->getColumnDimension('G')->setWidth(15); // Price
                $sheet->getColumnDimension('H')->setWidth(40); // Notes

                // Enable text wrapping for notes column
                $sheet->getStyle("H{$dataStartRow}:H{$dataEndRow}")->getAlignment()->setWrapText(true);
            },
        ];
    }
}
