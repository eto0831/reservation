<?php

namespace App\Console\Commands;

use App\Models\Reservation;
use App\Jobs\SendReminderEmailJob;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendDailyReminderEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails to users with reservations for today';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = Carbon::now()->toDateString();
        Log::info('SendDailyReminderEmails: Fetching reservations for ' . $today);

        $reservations = Reservation::whereDate('reserve_date', $today)->get();

        if ($reservations->isEmpty()) {
            Log::info('SendDailyReminderEmails: No reservations found for today.');
        }

        foreach ($reservations as $reservation) {
            Log::info('SendDailyReminderEmails: Dispatching job for reservation ID ' . $reservation->id);
            SendReminderEmailJob::dispatch($reservation);
        }

        $this->info('Reminder emails dispatched successfully.');
        Log::info('SendDailyReminderEmails: Command execution completed.');
    }
}
