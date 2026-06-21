<?php

namespace Tests\Unit\Models;

use App\Enums\AuctionStatus;
use App\Enums\BidStatus;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\User;
use Tests\TestCase;

class AuctionTest extends TestCase
{
    public function test_current_price_returns_start_price_when_no_bids(): void
    {
        $auction = Auction::factory()->create(['start_price' => 5000.0]);

        $this->assertEquals(5000.0, $auction->currentPrice());
    }

    public function test_current_price_returns_highest_bid(): void
    {
        $auction = Auction::factory()->create(['start_price' => 1000.0]);
        $user    = User::factory()->create();

        Bid::factory()->create(['auction_id' => $auction->id, 'user_id' => $user->id, 'price' => 1500.0]);
        Bid::factory()->create(['auction_id' => $auction->id, 'user_id' => $user->id, 'price' => 2000.0]);

        $this->assertEquals(2000.0, $auction->currentPrice());
    }

    public function test_minimum_next_bid_is_correctly_calculated(): void
    {
        $auction = Auction::factory()->create(['start_price' => 1000.0, 'price_step' => 10]);

        $this->assertEquals(1100.0, $auction->minimumNextBid());
    }

    public function test_close_sets_status_to_closed(): void
    {
        $auction = Auction::factory()->create();

        $auction->close();

        $this->assertEquals(AuctionStatus::Closed, $auction->fresh()->status);
    }

    public function test_close_marks_highest_bidder_as_winner(): void
    {
        $auction = Auction::factory()->create();
        $user    = User::factory()->create();

        $low  = Bid::factory()->create(['auction_id' => $auction->id, 'user_id' => $user->id, 'price' => 1000.0]);
        $high = Bid::factory()->create(['auction_id' => $auction->id, 'user_id' => $user->id, 'price' => 5000.0]);

        $auction->close();

        $this->assertEquals(BidStatus::Win,  $high->fresh()->status);
        $this->assertEquals(BidStatus::Lose, $low->fresh()->status);
    }

    public function test_close_with_no_bids_does_not_crash(): void
    {
        $auction = Auction::factory()->create();

        $auction->close();

        $this->assertEquals(AuctionStatus::Closed, $auction->fresh()->status);
    }

    public function test_close_with_single_bid_marks_it_as_winner(): void
    {
        $auction = Auction::factory()->create();
        $bid     = Bid::factory()->create(['auction_id' => $auction->id, 'user_id' => User::factory()->create()->id]);

        $auction->close();

        $this->assertEquals(BidStatus::Win, $bid->fresh()->status);
    }

    public function test_status_helpers(): void
    {
        $hidden = Auction::factory()->hidden()->create();
        $opened = Auction::factory()->create();
        $closed = Auction::factory()->closed()->create();

        $this->assertTrue($hidden->isHidden());
        $this->assertFalse($hidden->isOpened());

        $this->assertTrue($opened->isOpened());
        $this->assertFalse($opened->isClosed());

        $this->assertTrue($closed->isClosed());
        $this->assertFalse($closed->isOpened());
    }
}
