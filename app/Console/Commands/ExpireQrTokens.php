<?php

namespace App\Console\Commands;

use App\Models\QrToken;
use Illuminate\Console\Command;

class ExpireQrTokens extends Command
{
    protected $signature = 'qr:expire';

    protected $description = 'Mark expired QR tokens as expired in the database';

    public function handle()
    {
        $updated = QrToken::where('status', 'active')
            ->where('expires_at', '<=', time())
            ->update(['status' => 'expired']);

        $this->info("Marked {$updated} QR tokens as expired.");
    }
}
