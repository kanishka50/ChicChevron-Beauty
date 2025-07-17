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
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;

class SalesReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle, WithCustomStartCell, WithEvents, WithColumnFormatting
{
    protected $filters;
    protected $reportService;
    protected $reportData;

    public function __construct($filters)
    {
        $this->filters = $filters;
        $this->reportService = new ReportService();
        $this->reportData = $this->reportService->getSalesReport($filters);
    }

    /**
     * Start writing from cell A7 to leave room for summary
     */
    public function startCell(): string
    {
        return 'A7';
    }

    /**
     * Set the worksheet title
     */
    public function title(): string
    {
        return 'Sales Report';
    }

    /**
     * Return the collection of sales data
     */
    public function collection()
    {
        return $this->reportData['product_sales'];
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
            'Quantity Sold',
            'Revenue (Rs.)',
            'Cost (Rs.)',
            'Profit (Rs.)',
            'Profit Margin %',
            'Orders',
            'Avg. Order Value'
        ];
    }

    /**
     * Map the data for each row
     */
    public function map($item): array
    {
        $avgOrderValue = $item->order_count > 0 
            ? $item->total_revenue / $item->order_count 
            : 0;

        return [
            $item->product->name,
            $item->product->brand->name ?? '-',
            $item->product->category->name ?? '-',
            $item->productVariant ? $item->productVariant->name : '-',
            $item->productVariant ? $item->productVariant->sku : $item->product->sku,
            $item->total_quantity,
            round($item->total_revenue, 2),
            round($item->total_cost, 2),
            round($item->profit, 2),
            round($item->profit_margin, 1),
            $item->order_count,
            round($avgOrderValue, 2)
        ];
    }

    /**
     * Define column formats
     */
    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'J' => NumberFormat::FORMAT_NUMBER_00,
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    /**
     * Apply styles to the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        // Style the heading row
        $sheet->getStyle('A7:L7')->applyFromArray([
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
        $sheet->getStyle('A7:L' . $highestRow)->applyFromArray([
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
                $sheet->setCellValue('A1', 'Sales Report');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 18,
                        'color' => ['rgb' => '1F2937'],
                    ],
                ]);
                
                // Add date range
                $sheet->setCellValue('A2', 'Period: ' . 
                    Carbon::parse($this->filters['start_date'])->format('M d, Y') . ' - ' . 
                    Carbon::parse($this->filters['end_date'])->format('M d, Y')
                );
                
                // Add generated date
                $sheet->setCellValue('A3', 'Generated: ' . now()->format('Y-m-d H:i:s'));
                
                // Add filter information
                $filterText = 'Filters: ';
                $hasFilters = false;
                
                if (!empty($this->filters['category_id'])) {
                    $category = \App\Models\Category::find($this->filters['category_id']);
                    $filterText .= 'Category=' . ($category->name ?? 'Unknown') . ' ';
                    $hasFilters = true;
                }
                if (!empty($this->filters['brand_id'])) {
                    $brand = \App\Models\Brand::find($this->filters['brand_id']);
                    $filterText .= 'Brand=' . ($brand->name ?? 'Unknown') . ' ';
                    $hasFilters = true;
                }
                if (!empty($this->filters['product_id'])) {
                    $product = \App\Models\Product::find($this->filters['product_id']);
                    $filterText .= 'Product=' . ($product->name ?? 'Unknown') . ' ';
                    $hasFilters = true;
                }
                
                if ($hasFilters) {
                    $sheet->setCellValue('A4', $filterText);
                }
                
                // Add summary box
                $sheet->setCellValue('E1', 'Sales Summary');
                $sheet->mergeCells('E1:G1');
                $sheet->getStyle('E1:G1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'EFF6FF'],
                    ],
                ]);
                
                // Summary statistics
                $sheet->setCellValue('E2', 'Total Revenue:');
                $sheet->setCellValue('G2', 'Rs. ' . number_format($this->reportData['summary']['total_revenue'], 2));
                
                $sheet->setCellValue('E3', 'Total Orders:');
                $sheet->setCellValue('G3', number_format($this->reportData['summary']['total_orders']));
                
                $sheet->setCellValue('E4', 'Avg Order Value:');
                $sheet->setCellValue('G4', 'Rs. ' . number_format($this->reportData['summary']['average_order_value'], 2));
                
                $sheet->setCellValue('E5', 'Total Discount:');
                $sheet->setCellValue('G5', 'Rs. ' . number_format($this->reportData['summary']['total_discount'], 2));
                
                // Profit metrics box
                $sheet->setCellValue('I1', 'Profit Metrics');
                $sheet->mergeCells('I1:K1');
                $sheet->getStyle('I1:K1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F0FDF4'],
                    ],
                ]);
                
                // Calculate total profit and margin
                $totalRevenue = $this->reportData['product_sales']->sum('total_revenue');
                $totalCost = $this->reportData['product_sales']->sum('total_cost');
                $totalProfit = $totalRevenue - $totalCost;
                $avgProfitMargin = $totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0;
                
                $sheet->setCellValue('I2', 'Total Profit:');
                $sheet->setCellValue('K2', 'Rs. ' . number_format($totalProfit, 2));
                
                $sheet->setCellValue('I3', 'Avg Profit Margin:');
                $sheet->setCellValue('K3', number_format($avgProfitMargin, 1) . '%');
                
                $sheet->setCellValue('I4', 'Products Sold:');
                $sheet->setCellValue('K4', number_format($this->reportData['product_sales']->sum('total_quantity')));
                
                $sheet->setCellValue('I5', 'Unique Items:');
                $sheet->setCellValue('K5', number_format($this->reportData['product_sales']->count()));
                
                // Apply borders to summary boxes
                $sheet->getStyle('E1:G5')->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                        ],
                    ],
                ]);
                
                $sheet->getStyle('I1:K5')->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                        ],
                    ],
                ]);
                
                // Color code profit margins
                $highestRow = $sheet->getHighestRow();
                for ($row = 8; $row <= $highestRow; $row++) {
                    $profitMargin = $sheet->getCell('J' . $row)->getValue();
                    
                    if ($profitMargin > 30) {
                        $sheet->getStyle('J' . $row)->applyFromArray([
                            'font' => ['color' => ['rgb' => '059669']],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'D1FAE5'],
                            ],
                        ]);
                    } elseif ($profitMargin > 20) {
                        $sheet->getStyle('J' . $row)->applyFromArray([
                            'font' => ['color' => ['rgb' => 'D97706']],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'FEF3C7'],
                            ],
                        ]);
                    } else {
                        $sheet->getStyle('J' . $row)->applyFromArray([
                            'font' => ['color' => ['rgb' => 'DC2626']],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'FEE2E2'],
                            ],
                        ]);
                    }
                }
                
                // Add percentage symbol to profit margin column
                $sheet->getStyle('J8:J' . $highestRow)
                    ->getNumberFormat()
                    ->setFormatCode('0.0"%"');
                
                // Freeze the header row
                $sheet->freezePane('A8');
                
                // Set column widths for better readability
                $sheet->getColumnDimension('A')->setWidth(30);
                $sheet->getColumnDimension('D')->setWidth(20);
                
                // Add a chart placeholder note
                $lastRow = $highestRow + 2;
                $sheet->setCellValue('A' . $lastRow, 'Note: For visual charts and detailed analytics, please view the report in the admin panel.');
                $sheet->getStyle('A' . $lastRow)->applyFromArray([
                    'font' => ['italic' => true, 'color' => ['rgb' => '6B7280']],
                ]);
            },
        ];
    }
}