<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Models\UserSubscription;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use App\Http\Controllers\API\GroupAPIController;
use App\Models\Group;

class CheckSuccessfulTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-successful-transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $transactions = Transaction::where('status', 'successful')->where('processed', false)->get();

        foreach ($transactions as $transaction) {
            // Check if transaction corresponds to a subscription plan
            $subPlan = Subscription::where('id', $transaction->subscription_plan_id)->first();
            if ($transaction->subscription_plan_id) {
                $UserSubscription = UserSubscription::create([
                    'user_id' => $transaction->user_id,
                    'subscription_plan_id' => $transaction->subscription_plan_id,
                    'transaction_id' => $transaction->id,
                    'plan_amount' => $transaction->amount,
                    'plan_frequency' => 1, // Assuming monthly
                    'starts_at' => Carbon::now(),
                    'ends_at' => Carbon::now()->addMonth(),
                    'sms_limit' => 100, 
                    'status' => 1, 
                ]);

                
                $user = User::find($transaction->user_id);
                if(count(json_decode($subPlan->permissions)) > 0){
                    foreach (json_decode($subPlan->permissions) as $permission) {
                        $user->givePermissionTo($permission);
                    }
                }

                
                $transaction->processed = true;
                $transaction->save();
                if(count(json_decode($subPlan->chat_group)) > 0){
                    foreach (json_decode($subPlan->chat_group) as $chat_group_id) {
                        $group = Group::where('id',$chat_group_id)->first();
                        $groupApi = new GroupAPIController(app('App\Repositories\GroupRepository'));
                        $groupApi->addMembersCron($group, $user->id);
                    }
                }
            }
        }

        $this->info('Checked transactions and updated subscriptions.');
    }
}
