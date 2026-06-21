<?php

namespace App\Enums;

enum AuctionStatus: string
{
    case Hidden = 'Hidden';
    case Opened = 'Opened';
    case Closed = 'Closed';

    public function label(): string
    {
        return match($this) {
            AuctionStatus::Hidden => 'Скрыт',
            AuctionStatus::Opened => 'Открыт',
            AuctionStatus::Closed => 'Завершён',
        };
    }
}
