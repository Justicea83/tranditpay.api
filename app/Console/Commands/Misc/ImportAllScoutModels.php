<?php

namespace App\Console\Commands\Misc;

use App\Models\Copilot\Copilot;
use App\Models\Form\Form;
use App\Models\Merchant\Merchant;
use App\Models\Payment\PaymentType;
use App\Models\Payment\SettlementBank;
use App\Models\Payment\Tax;
use App\Models\Payment\Transaction;
use App\Models\User;
use Illuminate\Console\Command;

class ImportAllScoutModels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scout:import_all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command imports all scout models';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $classes = [
            Transaction::class,
            Copilot::class,
            PaymentType::class,
            SettlementBank::class,
            Form::class,
            Merchant::class,
            Tax::class,
            User::class
        ];
        foreach( $classes as $class ){
            $this->call('scout:import', [
                'model' => $class
            ]);
        }
    }
}
