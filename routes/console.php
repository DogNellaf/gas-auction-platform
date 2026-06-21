<?php

use App\Enums\AuctionStatus;
use App\Enums\BidStatus;
use App\Models\Auction;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    Auction::where('status', AuctionStatus::Opened)
        ->where('end_at', '<=', now())
        ->get()
        ->each(fn(Auction $auction) => $auction->close());
})->everyMinute()->name('auctions:auto-close');
