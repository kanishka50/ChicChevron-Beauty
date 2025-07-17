import Chart from 'chart.js/auto';

// Store chart instances globally to destroy them before recreating
let chartInstances = {};

// Initialize all charts when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize date range pickers if they exist
    initializeDateRangePickers();
    
    // Initialize all charts on the page
    initializeCharts();
    
    // Setup event listeners
    setupEventListeners();
    
    // Setup export buttons
    setupExportButtons();
});

// Initialize date range pickers
function initializeDateRangePickers() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    
    if (startDateInput && endDateInput) {
        // Set max date to today
        const today = new Date().toISOString().split('T')[0];
        endDateInput.setAttribute('max', today);
        
        // Update end date min when start date changes
        startDateInput.addEventListener('change', function() {
            endDateInput.setAttribute('min', this.value);
        });
        
        // Update start date max when end date changes
        endDateInput.addEventListener('change', function() {
            startDateInput.setAttribute('max', this.value);
        });
    }
}

// Initialize all charts
function initializeCharts() {
    // Sales page charts
    if (document.getElementById('dailySalesChart')) {
        createDailySalesChart();
    }
    if (document.getElementById('topProductsChart')) {
        createTopProductsChart();
    }
    if (document.getElementById('salesByCategoryChart')) {
        createSalesByCategoryChart();
    }
    if (document.getElementById('paymentMethodsChart')) {
        createPaymentMethodsChart();
    }
    
    // Inventory page charts
    if (document.getElementById('stockLevelsChart')) {
        createStockLevelsChart();
    }
    if (document.getElementById('inventoryValueChart')) {
        createInventoryValueChart();
    }
    if (document.getElementById('stockMovementChart')) {
        createStockMovementChart();
    }
    
    // Customer page charts
    if (document.getElementById('registrationTrendChart')) {
        createRegistrationTrendChart();
    }
    if (document.getElementById('customerDistributionChart')) {
        createCustomerDistributionChart();
    }
    if (document.getElementById('topCustomersChart')) {
        createTopCustomersChart();
    }
    if (document.getElementById('orderFrequencyChart')) {
        createOrderFrequencyChart();
    }
}

// Setup event listeners
function setupEventListeners() {
    // Filter form submission
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        // Auto-submit form when filters change
        filterForm.querySelectorAll('select, input[type="date"]').forEach(input => {
            input.addEventListener('change', function() {
                filterForm.submit();
            });
        });
    }
    
    // Chart type selector for dynamic updates
    const chartTypeSelector = document.getElementById('chartType');
    if (chartTypeSelector) {
        chartTypeSelector.addEventListener('change', function() {
            updateChart(this.value);
        });
    }
    
    // Print report button
    const printButton = document.getElementById('printReport');
    if (printButton) {
        printButton.addEventListener('click', function() {
            window.print();
        });
    }
}

// Setup export buttons
function setupExportButtons() {
    // Excel export
    const excelExportBtn = document.getElementById('exportExcel');
    if (excelExportBtn) {
        excelExportBtn.addEventListener('click', function() {
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            params.append('format', 'excel');
            
            window.location.href = this.dataset.url + '?' + params.toString();
        });
    }
    
    // PDF export
    const pdfExportBtn = document.getElementById('exportPdf');
    if (pdfExportBtn) {
        pdfExportBtn.addEventListener('click', function() {
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            params.append('format', 'pdf');
            
            window.location.href = this.dataset.url + '?' + params.toString();
        });
    }
}

// Create daily sales chart
function createDailySalesChart() {
    const ctx = document.getElementById('dailySalesChart').getContext('2d');
    const chartData = JSON.parse(document.getElementById('dailySalesChartData').textContent);
    
    destroyChart('dailySalesChart');
    
    chartInstances['dailySalesChart'] = new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rs. ' + value.toLocaleString();
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    grid: {
                        drawOnChartArea: false,
                    },
                },
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                if (context.dataset.label.includes('Revenue')) {
                                    label += 'Rs. ' + context.parsed.y.toLocaleString();
                                } else {
                                    label += context.parsed.y;
                                }
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
}

// Create top products chart
function createTopProductsChart() {
    const ctx = document.getElementById('topProductsChart').getContext('2d');
    const chartData = JSON.parse(document.getElementById('topProductsChartData').textContent);
    
    destroyChart('topProductsChart');
    
    chartInstances['topProductsChart'] = new Chart(ctx, {
        type: 'bar',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rs. ' + value.toLocaleString();
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                if (context.dataset.label.includes('Revenue')) {
                                    label += 'Rs. ' + context.parsed.y.toLocaleString();
                                } else {
                                    label += context.parsed.y;
                                }
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
}

// Create sales by category chart
function createSalesByCategoryChart() {
    const ctx = document.getElementById('salesByCategoryChart').getContext('2d');
    const chartData = JSON.parse(document.getElementById('salesByCategoryChartData').textContent);
    
    destroyChart('salesByCategoryChart');
    
    chartInstances['salesByCategoryChart'] = new Chart(ctx, {
        type: 'doughnut',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed !== null) {
                                label += 'Rs. ' + context.parsed.toLocaleString();
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
}

// Create payment methods chart
function createPaymentMethodsChart() {
    const ctx = document.getElementById('paymentMethodsChart').getContext('2d');
    const chartData = JSON.parse(document.getElementById('paymentMethodsChartData').textContent);
    
    destroyChart('paymentMethodsChart');
    
    chartInstances['paymentMethodsChart'] = new Chart(ctx, {
        type: 'pie',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed !== null) {
                                label += 'Rs. ' + context.parsed.toLocaleString();
                                
                                // Calculate percentage
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                label += ' (' + percentage + '%)';
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
}

// Create stock levels chart
function createStockLevelsChart() {
    const ctx = document.getElementById('stockLevelsChart').getContext('2d');
    const chartData = JSON.parse(document.getElementById('stockLevelsChartData').textContent);
    
    destroyChart('stockLevelsChart');
    
    chartInstances['stockLevelsChart'] = new Chart(ctx, {
        type: 'pie',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
}

// Create inventory value chart
function createInventoryValueChart() {
    const ctx = document.getElementById('inventoryValueChart').getContext('2d');
    const chartData = JSON.parse(document.getElementById('inventoryValueChartData').textContent);
    
    destroyChart('inventoryValueChart');
    
    chartInstances['inventoryValueChart'] = new Chart(ctx, {
        type: 'bar',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rs. ' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rs. ' + context.parsed.x.toLocaleString();
                        }
                    }
                }
            }
        }
    });
}

// Create stock movement chart
function createStockMovementChart() {
    const ctx = document.getElementById('stockMovementChart').getContext('2d');
    const chartData = JSON.parse(document.getElementById('stockMovementChartData').textContent);
    
    destroyChart('stockMovementChart');
    
    chartInstances['stockMovementChart'] = new Chart(ctx, {
        type: 'bar',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    stacked: false,
                },
                y: {
                    beginAtZero: true,
                }
            }
        }
    });
}

// Create registration trend chart
function createRegistrationTrendChart() {
    const ctx = document.getElementById('registrationTrendChart').getContext('2d');
    const chartData = JSON.parse(document.getElementById('registrationTrendChartData').textContent);
    
    destroyChart('registrationTrendChart');
    
    chartInstances['registrationTrendChart'] = new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
}

// Create customer distribution chart
function createCustomerDistributionChart() {
    const ctx = document.getElementById('customerDistributionChart').getContext('2d');
    const chartData = JSON.parse(document.getElementById('customerDistributionChartData').textContent);
    
    destroyChart('customerDistributionChart');
    
    chartInstances['customerDistributionChart'] = new Chart(ctx, {
        type: 'bar',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
}

// Create top customers chart
function createTopCustomersChart() {
    const ctx = document.getElementById('topCustomersChart').getContext('2d');
    const chartData = JSON.parse(document.getElementById('topCustomersChartData').textContent);
    
    destroyChart('topCustomersChart');
    
    chartInstances['topCustomersChart'] = new Chart(ctx, {
        type: 'bar',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rs. ' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rs. ' + context.parsed.x.toLocaleString();
                        }
                    }
                }
            }
        }
    });
}

// Create order frequency chart
function createOrderFrequencyChart() {
    const ctx = document.getElementById('orderFrequencyChart').getContext('2d');
    const chartData = JSON.parse(document.getElementById('orderFrequencyChartData').textContent);
    
    destroyChart('orderFrequencyChart');
    
    chartInstances['orderFrequencyChart'] = new Chart(ctx, {
        type: 'bar',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
}

// Destroy existing chart instance
function destroyChart(chartId) {
    if (chartInstances[chartId]) {
        chartInstances[chartId].destroy();
    }
}

// Update chart dynamically (for AJAX updates)
async function updateChart(chartType) {
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    formData.append('chart_type', chartType);
    
    try {
        const response = await fetch('/admin/reports/sales/data?' + new URLSearchParams(formData));
        const data = await response.json();
        
        // Update the appropriate chart based on type
        switch(chartType) {
            case 'daily_sales':
                updateDailySalesChart(data);
                break;
            case 'top_products':
                updateTopProductsChart(data);
                break;
            case 'sales_by_category':
                updateSalesByCategoryChart(data);
                break;
            case 'payment_methods':
                updatePaymentMethodsChart(data);
                break;
        }
    } catch (error) {
        console.error('Error updating chart:', error);
    }
}

// Update specific charts with new data
function updateDailySalesChart(data) {
    if (chartInstances['dailySalesChart']) {
        chartInstances['dailySalesChart'].data = data;
        chartInstances['dailySalesChart'].update();
    }
}

function updateTopProductsChart(data) {
    if (chartInstances['topProductsChart']) {
        chartInstances['topProductsChart'].data = data;
        chartInstances['topProductsChart'].update();
    }
}

function updateSalesByCategoryChart(data) {
    if (chartInstances['salesByCategoryChart']) {
        chartInstances['salesByCategoryChart'].data = data;
        chartInstances['salesByCategoryChart'].update();
    }
}

function updatePaymentMethodsChart(data) {
    if (chartInstances['paymentMethodsChart']) {
        chartInstances['paymentMethodsChart'].data = data;
        chartInstances['paymentMethodsChart'].update();
    }
}

// Export chart as image
window.exportChartAsImage = function(chartId) {
    const chart = chartInstances[chartId];
    if (chart) {
        const url = chart.toBase64Image();
        const link = document.createElement('a');
        link.download = chartId + '.png';
        link.href = url;
        link.click();
    }
}

// Format currency for display
window.formatCurrency = function(amount) {
    return 'Rs. ' + parseFloat(amount).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

// Print specific section
window.printSection = function(sectionId) {
    const section = document.getElementById(sectionId);
    const printWindow = window.open('', '', 'height=600,width=800');
    
    printWindow.document.write('<html><head><title>Print Report</title>');
    printWindow.document.write('<link rel="stylesheet" href="/css/app.css">');
    printWindow.document.write('</head><body>');
    printWindow.document.write(section.innerHTML);
    printWindow.document.write('</body></html>');
    
    printWindow.document.close();
    printWindow.print();
}