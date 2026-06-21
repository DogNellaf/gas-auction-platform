<?php

namespace App\Enums;

enum BidStatus: string
{
    case Waiting = 'Waiting';
    case Win = 'Win';
    case Lose = 'Lose';

    public function label(): string
    {
        return match($this) {
            BidStatus::Waiting => 'Ожидание завершения аукциона',
            BidStatus::Win => 'Победа',
            BidStatus::Lose => 'Проигрыш',
        };
    }
}
