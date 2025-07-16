<?php

namespace App\Services;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class InvoiceService
{
    /**
     * Generate invoice for an order
     */
    public function generateInvoice(Order $order)
    {
        try {
            // Load necessary relationships
            $order->load([
                'user',
                'items.product.brand',
                'items.productVariant'
            ]);

            // Get or generate invoice number
            $invoiceNumber = $this->getOrCreateInvoiceNumber($order);
            
            // Prepare invoice data
            $invoiceData = $this->prepareInvoiceData($order, $invoiceNumber);
            
            // Generate PDF
            $pdf = PDF::loadView('admin.orders.invoice', $invoiceData);
            $pdf->setPaper('A4', 'portrait');
            
            // Save invoice to storage (optional for record keeping)
            $invoiceFileName = "invoice-{$order->order_number}-{$invoiceNumber}.pdf";
            $pdfContent = $pdf->output();
            Storage::disk('local')->put("invoices/{$invoiceFileName}", $pdfContent);
            
            // Log invoice generation
            Log::info('Invoice generated', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'invoice_number' => $invoiceNumber,
                'file_name' => $invoiceFileName
            ]);

            return [
                'success' => true,
                'invoice_number' => $invoiceNumber,
                'pdf_content' => $pdfContent,
                'file_name' => $invoiceFileName,
                'file_path' => "invoices/{$invoiceFileName}"
            ];

        } catch (\Exception $e) {
            Log::error('Invoice generation failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            
            throw new \Exception('Failed to generate invoice: ' . $e->getMessage());
        }
    }

    /**
     * Get or create sequential invoice number
     */
    protected function getOrCreateInvoiceNumber(Order $order)
    {
        // Check if order already has an invoice number stored
        if ($order->invoice_number) {
            return $order->invoice_number;
        }

        // Generate new sequential invoice number
        $lastInvoiceNumber = $this->getLastInvoiceNumber();
        $newInvoiceNumber = $lastInvoiceNumber + 1;
        
        // Format: INV-2025-0001
        $formattedInvoiceNumber = sprintf('INV-%s-%04d', date('Y'), $newInvoiceNumber);
        
        // Store invoice number in order (you may need to add this column to orders table)
        // For now, we'll store it in the notes or create a separate invoice_number field
        $order->update(['notes' => ($order->notes ? $order->notes . ' | ' : '') . "Invoice: {$formattedInvoiceNumber}"]);
        
        return $formattedInvoiceNumber;
    }

    /**
     * Get the last invoice number for sequential numbering
     */
    protected function getLastInvoiceNumber()
    {
        // For now, we'll count orders. In production, you'd want a separate invoices table
        $currentYear = date('Y');
        
        // Count orders created this year that have invoice numbers
        $yearlyInvoiceCount = Order::whereYear('created_at', $currentYear)
                                  ->where('notes', 'like', '%Invoice: INV-%')
                                  ->count();
        
        return $yearlyInvoiceCount;
    }

    /**
     * Prepare all data needed for invoice generation
     */
    protected function prepareInvoiceData(Order $order, $invoiceNumber)
    {
        // Company information (should be configurable)
        $companyInfo = [
            'name' => 'ChicChevron Beauty',
            'address' => 'Your Business Address',
            'city' => 'Ratnapura',
            'postal_code' => '70000',
            'country' => 'Sri Lanka',
            'phone' => '+94 XX XXX XXXX',
            'email' => 'info@chicchevronbeauty.com',
            'website' => 'www.chicchevronbeauty.com',
            'registration_no' => 'BR/XXX/XXXX', // Business registration number
            'tax_no' => 'VAT/XXX/XXXX' // Tax/VAT number if applicable
        ];

        // Calculate totals and taxes
        $calculations = $this->calculateInvoiceTotals($order);
        
        // Format order items with detailed information
        $formattedItems = $this->formatOrderItems($order->items);

        return [
            'order' => $order,
            'invoice_number' => $invoiceNumber,
            'invoice_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(30)->format('Y-m-d'), // 30 days payment terms
            'company' => $companyInfo,
            'customer' => [
                'name' => $order->shipping_name,
                'email' => $order->user->email ?? 'N/A',
                'phone' => $order->shipping_phone,
                'address' => $order->full_shipping_address
            ],
            'items' => $formattedItems,
            'calculations' => $calculations,
            'payment_info' => [
                'method' => strtoupper($order->payment_method),
                'status' => ucfirst($order->payment_status),
                'reference' => $order->payment_reference
            ],
            'notes' => $this->getInvoiceNotes($order),
            'terms' => $this->getInvoiceTerms()
        ];
    }

    /**
     * Format order items for invoice display
     */
    protected function formatOrderItems($orderItems)
{
    return $orderItems->map(function ($item) {
        $description = $item->product_name;
        
        // Add variant information to description - supports both old and new system
        if ($item->productVariant) {
            // New variant system
            $variantText = [];
            
            if ($item->productVariant->size) {
                $variantText[] = "Size: {$item->productVariant->size}";
            }
            if ($item->productVariant->color) {
                $variantText[] = "Color: {$item->productVariant->color}";
            }
            if ($item->productVariant->scent) {
                $variantText[] = "Scent: {$item->productVariant->scent}";
            }
            
            if (!empty($variantText)) {
                $description .= ' (' . implode(', ', $variantText) . ')';
            }
        } elseif ($item->variant_details) {
            // Fallback for old orders with variant_details JSON
            $variantDetails = json_decode($item->variant_details, true);
            if (is_array($variantDetails)) {
                $variantText = [];
                foreach ($variantDetails as $key => $value) {
                    if ($value) {
                        $variantText[] = ucfirst($key) . ": " . $value;
                    }
                }
                if (!empty($variantText)) {
                    $description .= ' (' . implode(', ', $variantText) . ')';
                }
            }
        }

        return [
            'sku' => $item->productVariant->sku ?? $item->product_sku,
            'description' => $description,
            'quantity' => $item->quantity,
            'unit_price' => $item->unit_price,
            'discount' => $item->discount_amount,
            'line_total' => $item->total_price,
            'brand' => $item->product->brand->name ?? 'N/A'
        ];
    });
}

    /**
     * Calculate invoice totals including taxes if applicable
     */
    protected function calculateInvoiceTotals(Order $order)
    {
        $subtotal = $order->subtotal;
        $discount = $order->discount_amount;
        $shipping = $order->shipping_amount;
        
        // Calculate tax if applicable (Sri Lanka VAT is typically 15%)
        $taxRate = 0; // Set to 0.15 if VAT applies
        $taxAmount = ($subtotal - $discount + $shipping) * $taxRate;
        
        $total = $subtotal - $discount + $shipping + $taxAmount;

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'shipping' => $shipping,
            'tax_rate' => $taxRate * 100, // Convert to percentage
            'tax_amount' => $taxAmount,
            'total' => $total,
            'currency' => 'LKR'
        ];
    }

    /**
     * Get invoice-specific notes
     */
    protected function getInvoiceNotes(Order $order)
    {
        $notes = [];
        
        if ($order->payment_method === 'cod') {
            $notes[] = 'Payment Method: Cash on Delivery';
        } else {
            $notes[] = 'Payment Method: Online Payment (PayHere)';
            if ($order->payment_reference) {
                $notes[] = "Payment Reference: {$order->payment_reference}";
            }
        }
        
        if ($order->notes) {
            $notes[] = "Order Notes: {$order->notes}";
        }
        
        return $notes;
    }

    /**
     * Get standard invoice terms and conditions
     */
    protected function getInvoiceTerms()
    {
        return [
            'All sales are final unless otherwise specified.',
            'Please contact customer service for any inquiries about this invoice.',
            'Thank you for choosing ChicChevron Beauty!',
        ];
    }

    /**
     * Get invoice file path if it exists
     */
    public function getInvoiceFilePath(Order $order)
    {
        $invoiceNumber = $this->extractInvoiceNumber($order);
        
        if (!$invoiceNumber) {
            return null;
        }
        
        $fileName = "invoice-{$order->order_number}-{$invoiceNumber}.pdf";
        $filePath = "invoices/{$fileName}";
        
        return Storage::disk('local')->exists($filePath) ? $filePath : null;
    }

    /**
     * Extract invoice number from order notes (temporary solution)
     */
    protected function extractInvoiceNumber(Order $order)
    {
        if (!$order->notes) {
            return null;
        }
        
        preg_match('/Invoice: (INV-\d{4}-\d{4})/', $order->notes, $matches);
        return $matches[1] ?? null;
    }

    /**
     * Check if order has an invoice generated
     */
    public function hasInvoice(Order $order)
    {
        return $this->extractInvoiceNumber($order) !== null;
    }

    /**
     * Delete invoice file (if needed for reprocessing)
     */
    public function deleteInvoice(Order $order)
    {
        $filePath = $this->getInvoiceFilePath($order);
        
        if ($filePath && Storage::disk('local')->exists($filePath)) {
            Storage::disk('local')->delete($filePath);
            return true;
        }
        
        return false;
    }

    /**
     * Bulk generate invoices for multiple orders
     */
    public function bulkGenerateInvoices($orders)
    {
        $results = [];
        
        foreach ($orders as $order) {
            try {
                $result = $this->generateInvoice($order);
                $results[] = [
                    'order_id' => $order->id,
                    'success' => true,
                    'invoice_number' => $result['invoice_number']
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'order_id' => $order->id,
                    'success' => false,
                    'error' => $e->getMessage()
                ];
            }
        }
        
        return $results;
    }
}