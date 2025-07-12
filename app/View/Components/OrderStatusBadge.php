<?php
namespace App\View\Components;

use Illuminate\View\Component;

class OrderStatusBadge extends Component
{
    public string $status;
    public string $size;
    
    public function __construct(string $status, string $size = 'md')
    {
        $this->status = $status;
        $this->size = $size;
    }

    public function render()
    {
        return view('components.order-status-badge');
    }
}