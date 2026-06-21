<?php

namespace Tests\Feature;

use App\Enums\AuctionStatus;
use App\Models\Auction;
use App\Models\User;
use Tests\TestCase;

class AuctionPublicTest extends TestCase
{
    public function test_index_page_shows_open_auctions(): void
    {
        Auction::factory()->count(3)->create();
        Auction::factory()->hidden()->create();
        Auction::factory()->closed()->create();

        $this->get('/')->assertOk()->assertViewHas('auctions');
    }

    public function test_hidden_and_closed_auctions_not_shown_on_index(): void
    {
        $hidden = Auction::factory()->hidden()->create();
        $closed = Auction::factory()->closed()->create();
        $open   = Auction::factory()->create();

        $response = $this->get('/');

        $response->assertOk();
        $auctions = $response->viewData('auctions');

        $ids = $auctions->pluck('id')->toArray();
        $this->assertContains($open->id, $ids);
        $this->assertNotContains($hidden->id, $ids);
        $this->assertNotContains($closed->id, $ids);
    }

    public function test_auction_detail_page_is_accessible(): void
    {
        $auction = Auction::factory()->create();

        $this->get("/auctions/{$auction->id}")->assertOk();
    }

    public function test_guest_cannot_access_bid_form(): void
    {
        $auction = Auction::factory()->create();

        $this->get("/auctions/{$auction->id}/bid")->assertForbidden();
    }

    public function test_authenticated_user_can_access_bid_form(): void
    {
        $user    = User::factory()->create();
        $auction = Auction::factory()->create();

        $this->actingAs($user)->get("/auctions/{$auction->id}/bid")->assertOk();
    }

    public function test_guest_cannot_submit_bid(): void
    {
        $auction = Auction::factory()->create(['start_price' => 1000.0, 'price_step' => 10]);

        $this->post("/auctions/{$auction->id}/bid", ['price' => 2000])->assertForbidden();
    }

    public function test_user_can_submit_valid_bid(): void
    {
        $user    = User::factory()->create();
        $auction = Auction::factory()->create(['start_price' => 1000.0, 'price_step' => 10]);

        $response = $this->actingAs($user)->post("/auctions/{$auction->id}/bid", ['price' => 1200]);

        $response->assertRedirect('/home');
        $this->assertDatabaseHas('requests', [
            'user_id'    => $user->id,
            'auction_id' => $auction->id,
            'price'      => 1200,
        ]);
    }

    public function test_bid_below_minimum_is_rejected(): void
    {
        $user    = User::factory()->create();
        $auction = Auction::factory()->create(['start_price' => 1000.0, 'price_step' => 10]);

        // Minimum is 1100; submitting 1050 should fail
        $response = $this->actingAs($user)->post("/auctions/{$auction->id}/bid", ['price' => 1050]);

        $response->assertSessionHasErrors('price');
        $this->assertDatabaseMissing('requests', ['user_id' => $user->id]);
    }
}
