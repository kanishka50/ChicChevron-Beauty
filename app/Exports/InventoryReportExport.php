<?php

namespace App\Exports;

use App\Services\ReportService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class InventoryReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle, WithCustomStartCell, WithEvents
{
    protected $filters;
    protected $reportService;
    protected $reportData;

    public function __construct($filters)
    {
        $this->filters = $filters;
        $this->reportService = new ReportService();
        $this->reportData = $this->reportService->getInventoryReport($filters);
    }

    /**
     * Start writing from cell A5 to leave room for summary
     */
    public function startCell(): string
    {
        return 'A5';
    }

    /**
     * Set the worksheet title
     */
    public function title(): string
    {
        return 'Inventory Report';
    }

    /**
     * Return the collection of inventory items
     */
    public function collection()
    {
        return $this->reportData['items'];
    }

    /**
     * Define the headings for the export
     */
    public function headings(): array
    {
        return [
            'Product Name',
            'Brand',
            'Category',
            'Variant',
            'SKU',
            'Current Stock',
            'Reserved Stock',
            'Available Stock',
            'Low Stock Threshold',
            'Unit Cost (Rs.)',
            'Stock Value (Rs.)',
            'Status',
            'Last Updated'
        ];
    }

    /**
     * Map the data for each row
     */
    public function map($inventory): array
    {
        // Determine status
        if ($inventory->current_stock == 0) {
            $status = 'Out of Stock';
        } elseif ($inventory->available_stock <= $inventory->low_stock_threshold) {
            $status = 'Low Stock';
        } else {
            $status = 'In Stock';
        }

        return [
            $inventory->product->name,
            $inventory->product->brand->name ?? '-',
            $inventory->product->category->name ?? '-',
            $inventory->productVariant->name,
            $inventory->productVariant->sku,
            $inventory->current_stock,
            $inventory->reserved_stock,
            $inventory->available_stock,
            $inventory->low_stock_threshold,
            number_format($inventory->productVariant->cost_price, 2),
            number_format($inventory->stock_value, 2),
            $status,
            $inventory->updated_at->format('Y-m-d H:i:s')
        ];
    }

    /**
     * Apply styles to the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        // Style the heading row
        $sheet->getStyle('A5:M5')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2563EB'],
            ],
        ]);

        // Apply borders to all data
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A5:M' . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        return [];
    }

    /**
     * Register events to add summary information
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Add title
                $sheet->setCellValue('A1', 'Inventory Report');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                    ],
                ]);
                
                // Add summary information
                $sheet->setCellValue('A2', 'Generated: ' . now()->format('Y-m-d H:i:s'));
                
                // Add filter information
                $filterText = 'Filters: ';
                if ($this->filters['status'] !== 'all') {
                    $filterText .= 'Status=' . ucfirst($this->filters['status']) . ' ';
                }
                if (!empty($this->filters['category_id'])) {
                    $category = \App\Models\Category::find($this->filters['category_id']);
                    $filterText .= 'Category=' . ($category->name ?? 'Unknown') . ' ';
                }
                if (!empty($this->filters['brand_id'])) {
                    $brand = \App\Models\Brand::find($this->filters['brand_id']);
                    $filterText .= 'Brand=' . ($brand->name ?? 'Unknown') . ' ';
                }
                $sheet->setCellValue('A3', $filterText);
                
                // Add summary statistics in the right corner
                $sheet->setCellValue('J1', 'Summary Statistics');
                $sheet->getStyle('J1:L1')->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E5E7EB'],
                    ],
                ]);
                
                $sheet->setCellValue('J2', 'Total Products:');
                $sheet->setCellValue('L2', $this->reportData['total_products']);
                
                $sheet->setCellValue('J3', 'Total Variants:');
                $sheet->setCellValue('L3', $this->reportData['total_variants']);
                
                $sheet->setCellValue('J4', 'Total Inventory Value:');
                $sheet->setCellValue('L4', 'Rs. ' . number_format($this->reportData['total_value'], 2));
                
                // Color code the status column based on value
                $highestRow = $sheet->getHighestRow();
                for ($row = 6; $row <= $highestRow; $row++) {
                    $status = $sheet->getCell('L' . $row)->getValue();
                    
                    if ($status === 'Out of Stock') {
                        $sheet->getStyle('L' . $row)->applyFromArray([
                            'font' => ['color' => ['rgb' => 'DC2626']],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'FEE2E2'],
                            ],
                        ]);
                    } elseif ($status === 'Low Stock') {
                        $sheet->getStyle('L' . $row)->applyFromArray([
                            'font' => ['color' => ['rgb' => 'D97706']],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'FEF3C7'],
                            ],
                        ]);
                    } else {
                        $sheet->getStyle('L' . $row)->applyFromArray([
                            'font' => ['color' => ['rgb' => '059669']],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'D1FAE5'],
                            ],
                        ]);
                    }
                }
                
                // Freeze the header row
                $sheet->freezePane('A6');
            },
        ];
    }
}