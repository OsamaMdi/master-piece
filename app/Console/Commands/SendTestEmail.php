<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTestEmail extends Command
{
    protected $signature = 'send:test-email';
    protected $description = 'Send a test email manually to verify mail configuration';

    public function handle()
    {
        Mail::raw('Test email from Laravel CLI command', function ($message) {
            $message->to('osamamadi521@gmail.com')->subject('Test Email');
        });

        $this->info('✅ Test email sent to osamamadi521@gmail.com');
    }
}
