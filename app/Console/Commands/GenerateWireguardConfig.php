<?php

namespace App\Console\Commands;

use App\Models\Subnet;
use Illuminate\Console\Command;

class GenerateWireguardConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-wireguard-config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates all interfaces for wireguard';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Subnet::all()->each(fn (Subnet $subnet) => $subnet->interfaceUp());
    }
}
