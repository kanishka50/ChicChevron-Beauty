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
use Carbon\Carbon;

class CustomerReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle, WithCustomStartCell, WithEvents
{
    protected $filters;
    protected $reportService;
    protected $reportData;

    public function __construct($filters)
    {
        $this->filters = $filters;
        $this->reportService = new ReportService();
        $this->reportData = $this->reportService->getCustomerReport($filters);
    }

    /**
     * Start writing from cell A6 to leave room for summary
     */
    public function startCell(): string
    {
        return 'A6';
    }

    /**
     * Set the worksheet title
     */
    public function title(): string
    {
        return 'Customer Report';
    }

    /**
     * Return the collection of customers
     */
    public function collection()
    {
        // Get all customers, not just the limited set
        return $this->reportData['customers'];
    }

    /**
     * Define the headings for the export
     */
    public function headings(): array
    {
        return [
            'Customer ID',
            'Name',
            'Email',
            'Phone',
            'Registration Date',
            'Email Verified',
            'Total Orders',
            'Total Spent (Rs.)',
            'Average Order Value (Rs.)',
            'Last Order Date',
            'Customer Type',
            'City',
            'District'
        ];
    }

    /**
     * Map the data for each row
     */
    public function map($customer): array
    {
        // Get customer's last order
        $lastOrder = $customer->orders()
            ->where('payment_status', 'completed')
            ->latest()
            ->first();

        // Determine customer type based on order count
        $customerType = 'New';
        if ($customer->orders_count > 10) {
            $customerType = 'VIP';
        } elseif ($customer->orders_count > 5) {
            $customerType = 'Regular';
        } elseif ($customer->orders_count > 1) {
            $customerType = 'Returning';
        }

        // Get customer's primary address
        $primaryAddress = $customer->addresses()->where('is_default', true)->first();

        return [
            $customer->id,
            $customer->name,
            $customer->email,
            $customer->phone ?? '-',
            $customer->created_at->format('Y-m-d'),
            $customer->email_verified_at ? 'Yes' : 'No',
            $customer->orders_count ?? 0,
            number_format($customer->orders_sum_total_amount ?? 0, 2),
            number_format($customer->average_order_value ?? 0, 2),
            $lastOrder ? $lastOrder->created_at->format('Y-m-d') : '-',
            $customerType,
            $primaryAddress ? $primaryAddress->city : '-',
            $primaryAddress ? $primaryAddress->district : '-'
        ];
    }

    /**
     * Apply styles to the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        // Style the heading row
        $sheet->getStyle('A6:M6')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '7C3AED'],
            ],
        ]);

        // Apply borders to all data
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A6:M' . $highestRow)->applyFromArray([
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
                $sheet->setCellValue('A1', 'Customer Report');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                    ],
                ]);
                
                // Add date range
                $sheet->setCellValue('A2', 'Period: ' . 
                    Carbon::parse($this->filters['start_date'])->format('M d, Y') . ' - ' . 
                    Carbon::parse($this->filters['end_date'])->format('M d, Y')
                );
                
                // Add generated date
                $sheet->setCellValue('A3', 'Generated: ' . now()->format('Y-m-d H:i:s'));
                
                // Add summary statistics box
                $sheet->setCellValue('E1', 'Summary Statistics');
                $sheet->mergeCells('E1:G1');
                $sheet->getStyle('E1:G1')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E5E7EB'],
                    ],
                ]);
                
                // Add statistics
                $sheet->setCellValue('E2', 'Total Customers:');
                $sheet->setCellValue('G2', number_format($this->reportData['total_customers']));
                
                $sheet->setCellValue('E3', 'New Customers:');
                $sheet->setCellValue('G3', number_format($this->reportData['new_customers']));
                
                $sheet->setCellValue('E4', 'Returning Customers:');
                $sheet->setCellValue('G4', number_format($this->reportData['returning_customers']));
                
                $sheet->setCellValue('E5', 'Average Order Value:');
                $sheet->setCellValue('G5', 'Rs. ' . number_format($this->reportData['average_order_value'], 2));
                
                // Additional metrics
                $sheet->setCellValue('I1', 'Period Metrics');
                $sheet->mergeCells('I1:K1');
                $sheet->getStyle('I1:K1')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E5E7EB'],
                    ],
                ]);
                
                $sheet->setCellValue('I2', 'Total Revenue:');
                $sheet->setCellValue('K2', 'Rs. ' . number_format($this->reportData['total_revenue'], 2));
                
                // Calculate retention rate
                $retentionRate = $this->reportData['total_customers'] > 0 
                    ? ($this->reportData['returning_customers'] / $this->reportData['total_customers']) * 100 
                    : 0;
                
                $sheet->setCellValue('I3', 'Retention Rate:');
                $sheet->setCellValue('K3', number_format($retentionRate, 1) . '%');
                
                // Apply borders to summary boxes
                $sheet->getStyle('E1:G5')->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);
                
                $sheet->getStyle('I1:K3')->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);
                
                // Color code customer types
                $highestRow = $sheet->getHighestRow();
                for ($row = 7; $row <= $highestRow; $row++) {
                    $customerType = $sheet->getCell('K' . $row)->getValue();
                    
                    $style = [];
                    switch ($customerType) {
                        case 'VIP':
                            $style = [
                                'font' => ['color' => ['rgb' => '7C3AED']],
                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => ['rgb' => 'EDE9FE'],
                                ],
                            ];
                            break;
                        case 'Regular':
                            $style = [
                                'font' => ['color' => ['rgb' => '2563EB']],
                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => ['rgb' => 'DBEAFE'],
                                ],
                            ];
                            break;
                        case 'Returning':
                            $style = [
                                'font' => ['color' => ['rgb' => '059669']],
                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => ['rgb' => 'D1FAE5'],
                                ],
                            ];
                            break;
                        case 'New':
                            $style = [
                                'font' => ['color' => ['rgb' => '6B7280']],
                            ];
                            break;
                    }
                    
                    if (!empty($style)) {
                        $sheet->getStyle('K' . $row)->applyFromArray($style);
                    }
                }
                
                // Format currency columns
                $sheet->getStyle('H7:I' . $highestRow)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.00');
                
                // Freeze the header row
                $sheet->freezePane('A7');
                
                // Auto-adjust column widths for better readability
                foreach(range('A', 'M') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}