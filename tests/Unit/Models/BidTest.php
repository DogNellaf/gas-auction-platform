<?php

namespace Tests\Unit\Models;

use App\Enums\BidStatus;
use App\Models\Bid;
use App\Models\User;
use App\Models\Auction;
use Tests\TestCase;

class BidTest extends TestCase
{
    public function test_bid_status_helpers(): void
    {
        $user    = User::factory()->create();
        $auction = Auction::factory()->create();

        $waiting = Bid::factory()->create(['user_id' => $user->id, 'auction_id' => $auction->id, 'status' => BidStatus::Waiting]);
        $win     = Bid::factory()->win()->create(['user_id' => $user->id, 'auction_id' => $auction->id]);
        $lose    = Bid::factory()->lose()->create(['user_id' => $user->id, 'auction_id' => $auction->id]);

        $this->assertTrue($waiting->isWaiting());
        $this->assertFalse($waiting->isWin());
        $this->assertFalse($waiting->isLose());

        $this->assertTrue($win->isWin());
        $this->assertFalse($win->isWaiting());

        $this->assertTrue($lose->isLose());
        $this->assertFalse($lose->isWin());
    }

    public function test_bid_belongs_to_user_and_auction(): void
    {
        $user    = User::factory()->create();
        $auction = Auction::factory()->create();
        $bid     = Bid::factory()->create(['user_id' => $user->id, 'auction_id' => $auction->id]);

        $this->assertEquals($user->id, $bid->user->id);
        $this->assertEquals($auction->id, $bid->auction->id);
    }

    public function test_bid_status_label(): void
    {
        $this->assertEquals('Ожидание завершения аукциона', BidStatus::Waiting->label());
        $this->assertEquals('Победа', BidStatus::Win->label());
        $this->assertEquals('Проигрыш', BidStatus::Lose->label());
    }

    public function test_bid_is_stored_in_requests_table(): void
    {
        $bid = Bid::factory()->create(['user_id' => User::factory()->create()->id, 'auction_id' => Auction::factory()->create()->id]);

        $this->assertDatabaseHas('requests', ['id' => $bid->id]);
    }
}
