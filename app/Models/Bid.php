<?php

namespace App\Models;

use App\Enums\BidStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bid extends Model
{
    protected $table = 'requests';

    protected $fillable = ['price', 'status', 'user_id', 'auction_id'];

    protected $casts = [
        'status' => BidStatus::class,
        'price'  => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function auction(): BelongsTo
    {
        return $this->belongsTo(Auction::class);
    }

    public function isWaiting(): bool
    {
        return $this->status === BidStatus::Waiting;
    }

    public function isWin(): bool
    {
        return $this->status === BidStatus::Win;
    }

    public function isLose(): bool
    {
        return $this->status === BidStatus::Lose;
    }
}
