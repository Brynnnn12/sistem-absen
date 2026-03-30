<?php

namespace App\Console\Commands;

use App\Jobs\SendMonthlyAttendanceReport;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

#[Signature('attendance:send-monthly-reports {--month=} {--year=}')]
#[Description('Send monthly attendance reports to all active employees')]
class SendMonthlyAttendanceReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:send-monthly-reports
                            {--month= : Month to generate report for (1-12)}
                            {--year= : Year to generate report for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send monthly attendance reports to all active employees via email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $month = $this->option('month') ?? now()->subMonth()->month;
        $year = $this->option('year') ?? now()->subMonth()->year;

        $this->info("Sending monthly attendance reports for {$month}/{$year}...");

        try {
            // Dispatch job to queue
            SendMonthlyAttendanceReport::dispatch($month, $year);

            $this->info("✅ Monthly attendance reports job dispatched successfully!");
            $this->info("Reports will be sent to all active employees for the period: {$month}/{$year}");

            Log::info("Monthly attendance reports job dispatched for {$month}/{$year}");

        } catch (\Exception $e) {
            $this->error("❌ Failed to dispatch monthly attendance reports job: " . $e->getMessage());
            Log::error("Failed to dispatch monthly attendance reports: " . $e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
