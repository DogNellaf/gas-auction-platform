<?php

namespace App\Models;

use App\Enums\AuctionStatus;
use App\Enums\BidStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Auction extends Model
{
    protected $fillable = ['start_price', 'end_at', 'price_step', 'status', 'description'];

    protected $casts = [
        'status'      => AuctionStatus::class,
        'start_price' => 'float',
        'price_step'  => 'float',
        'end_at'      => 'datetime',
    ];

    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }

    public function currentPrice(): float
    {
        return $this->bids()->max('price') ?? $this->start_price;
    }

    public function minimumNextBid(): float
    {
        return $this->currentPrice() * (1 + $this->price_step / 100);
    }

    public function isHidden(): bool
    {
        return $this->status === AuctionStatus::Hidden;
    }

    public function isOpened(): bool
    {
        return $this->status === AuctionStatus::Opened;
    }

    public function isClosed(): bool
    {
        return $this->status === AuctionStatus::Closed;
    }

    public function close(): void
    {
        $this->update(['status' => AuctionStatus::Closed]);

        $bids = $this->bids()->orderByDesc('price')->get();

        if ($bids->isEmpty()) {
            return;
        }

        $bids->first()->update(['status' => BidStatus::Win]);
        $bids->slice(1)->each(fn(Bid $bid) => $bid->update(['status' => BidStatus::Lose]));
    }
}
