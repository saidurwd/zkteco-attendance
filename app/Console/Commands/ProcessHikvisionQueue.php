<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:process-hikvision-queue')]
#[Description('Command description')]
class ProcessHikvisionQueue extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}
