// Dashboard functionality
class Dashboard {
    constructor() {
        this.statsRefreshInterval = 60000; // 60 seconds
        this.init();
    }

    init() {
        this.setupAutoRefresh();
        this.setupCharts();
    }

    setupAutoRefresh() {
        setInterval(() => {
            this.refreshStats();
        }, this.statsRefreshInterval);
    }

    async refreshStats() {
        try {
            const response = await fetch('/admin/dashboard/refresh-stats');
            const data = await response.json();
            
            this.updateStatCards(data);
        } catch (error) {
            console.error('Failed to refresh stats:', error);
        }
    }

    updateStatCards(data) {
        // Update Today's Sales
        const salesElement = document.querySelector('[data-stat="today_sales"]');
        if (salesElement) {
            salesElement.textContent = `LKR ${this.formatNumber(data.today_sales)}`;
        }

        // Update Orders Today
        const ordersElement = document.querySelector('[data-stat="today_orders"]');
        if (ordersElement) {
            ordersElement.textContent = data.today_orders;
        }

        // Update other stats similarly...
    }

    formatNumber(number) {
        return new Intl.NumberFormat('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(number);
    }

    setupCharts() {
        // If you want to add charts later
        // You can use Chart.js or similar library
    }
}

// Initialize dashboard when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new Dashboard();
});