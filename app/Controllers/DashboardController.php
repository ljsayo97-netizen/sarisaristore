<?php 

namespace App\Controllers;

use App\Models\InventoryModel;
use App\Models\SaleModel;
use App\Models\CustomerModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $inventoryModel = new InventoryModel();
        $saleModel = new SaleModel();
        $customerModel = new CustomerModel();

        // 1. Total Products
        $totalProducts = $inventoryModel->countAllResults();

        // 2. Low Stock Items (Threshold: 10)
        $lowStockItems = $inventoryModel->where('stock <=', 10)->countAllResults();

        // 3. Total Sales Today
        $today = date('Y-m-d');
        $todaySalesCount = $saleModel->where('date >=', $today . ' 00:00:00')
                                    ->where('date <=', $today . ' 23:59:59')
                                    ->countAllResults();

        // 4. Today's Profit (Simplification: Total Amount)
        $todayProfit = $saleModel->selectSum('total_amount')
                                ->where('date >=', $today . ' 00:00:00')
                                ->where('date <=', $today . ' 23:59:59')
                                ->first();
        $profitValue = $todayProfit['total_amount'] ?? 0;

        // 5. Recent Transactions (Join with users for names if available)
        $recentTransactions = $saleModel->orderBy('date', 'DESC')->limit(5)->findAll();

        // 6. Data for Sales Chart (Last 7 days)
        $chartData = $this->getSalesChartData($saleModel);

        $data = [
            'totalProducts' => $totalProducts,
            'lowStockItems' => $lowStockItems,
            'todaySalesCount' => $todaySalesCount,
            'todayProfit' => number_format($profitValue, 2),
            'recentTransactions' => $recentTransactions,
            'chartLabels' => json_encode($chartData['labels']),
            'chartValues' => json_encode($chartData['values']),
        ];

        return view('dashboard/index', $data);
    }

    private function getSalesChartData($saleModel)
    {
        $labels = [];
        $values = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $labels[] = date('D', strtotime($date));
            
            $dayTotal = $saleModel->selectSum('total_amount')
                                 ->where('date >=', $date . ' 00:00:00')
                                 ->where('date <=', $date . ' 23:59:59')
                                 ->first();
            $values[] = $dayTotal['total_amount'] ?? 0;
        }

        return ['labels' => $labels, 'values' => $values];
    }
}
