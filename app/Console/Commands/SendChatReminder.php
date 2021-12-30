<?php

namespace App\Console\Commands;

use App\Models\ChatReminder;
use App\Models\User;
use App\Notifications\ChatReminder as NotificationsChatReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class SendChatReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:chatreminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder notification';

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

        $chatReminders = ChatReminder::where('is_notification_sent',0)->where('is_active',1)->get();

        foreach($chatReminders as $chatReminder) {
            $currentTime = strtotime(now());
            $reminderTime = strtotime($chatReminder->reminder_date_time) - 900;
            if($reminderTime == $currentTime) {
                $user = User::find($chatReminder->user_id);
                Notification::send($user, new NotificationsChatReminder($chatReminder));
                $chatReminder = ChatReminder::find($chatReminder->id);
                $chatReminder->is_notification_sent = 1;
                if($chatReminder->save()) {
                    Log::info("Chat reminder is sent");
                }
            }


        }

    }
}
