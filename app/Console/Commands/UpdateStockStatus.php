<?php
namespace App\Console\Commands;

use App\Models\stock;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateStockStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:update-stock-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Stock status to In-Stock when in_stock_date is today';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today()->toDateString();

        $updated = Stock::whereDate('in_stock_date', $today)
            ->where('status', '!=', 'in_stock')
            ->update(['status' => 'in_stock']);

        $this->info("Stock updated successfully. Total updated: $updated");

        return Command::SUCCESS;
    }
}
