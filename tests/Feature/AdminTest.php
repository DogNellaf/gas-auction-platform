<?php

namespace Tests\Feature;

use App\Enums\AuctionStatus;
use App\Enums\BidStatus;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\User;
use Tests\TestCase;

class AdminTest extends TestCase
{
    private function admin(): User
    {
        return User::factory()->admin()->create();
    }

    // --- Access control ---

    public function test_guest_cannot_access_admin(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_regular_user_cannot_access_admin(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_admin_can_access_admin_panel(): void
    {
        $this->actingAs($this->admin())->get('/admin')->assertOk();
    }

    // --- Auctions ---

    public function test_admin_sees_all_auctions(): void
    {
        Auction::factory()->count(3)->create();

        $response = $this->actingAs($this->admin())->get('/admin');

        $response->assertOk();
        $this->assertCount(3, $response->viewData('auctions'));
    }

    public function test_admin_can_create_auction(): void
    {
        $response = $this->actingAs($this->admin())->post('/admin/auctions', [
            'start_price' => 5000,
            'end_at'      => now()->addDay()->format('Y-m-d H:i:s'),
            'price_step'  => 5,
            'description' => 'Test auction description',
        ]);

        $response->assertRedirect('/admin');
        $this->assertDatabaseHas('auctions', ['description' => 'Test auction description']);
    }

    public function test_auction_creation_validates_start_price(): void
    {
        $this->actingAs($this->admin())->post('/admin/auctions', [
            'start_price' => 0,
            'end_at'      => now()->addDay()->format('Y-m-d H:i:s'),
            'price_step'  => 5,
            'description' => 'Test',
        ])->assertSessionHasErrors('start_price');
    }

    public function test_auction_creation_validates_end_at_in_future(): void
    {
        $this->actingAs($this->admin())->post('/admin/auctions', [
            'start_price' => 1000,
            'end_at'      => now()->subHour()->format('Y-m-d H:i:s'),
            'price_step'  => 5,
            'description' => 'Test',
        ])->assertSessionHasErrors('end_at');
    }

    public function test_admin_can_update_auction(): void
    {
        $auction = Auction::factory()->create();

        $this->actingAs($this->admin())->patch("/admin/auctions/{$auction->id}", [
            'id'          => $auction->id,
            'start_price' => 9999,
            'end_at'      => now()->addWeek()->format('Y-m-d H:i:s'),
            'price_step'  => 3,
            'description' => 'Updated',
            'status'      => 'Opened',
        ])->assertRedirect('/admin');

        $this->assertDatabaseHas('auctions', ['id' => $auction->id, 'description' => 'Updated']);
    }

    public function test_admin_can_end_auction(): void
    {
        $auction = Auction::factory()->create();
        $user    = User::factory()->create();
        Bid::factory()->create(['auction_id' => $auction->id, 'user_id' => $user->id, 'price' => 2000]);

        $this->actingAs($this->admin())
            ->patch("/admin/auctions/{$auction->id}/end", ['id' => $auction->id])
            ->assertRedirect('/admin');

        $this->assertEquals(AuctionStatus::Closed, $auction->fresh()->status);
    }

    public function test_ending_auction_assigns_winner(): void
    {
        $auction = Auction::factory()->create();
        $user    = User::factory()->create();

        $low  = Bid::factory()->create(['auction_id' => $auction->id, 'user_id' => $user->id, 'price' => 1000]);
        $high = Bid::factory()->create(['auction_id' => $auction->id, 'user_id' => $user->id, 'price' => 5000]);

        $this->actingAs($this->admin())
            ->patch("/admin/auctions/{$auction->id}/end", ['id' => $auction->id]);

        $this->assertEquals(BidStatus::Win,  $high->fresh()->status);
        $this->assertEquals(BidStatus::Lose, $low->fresh()->status);
    }

    // --- Bids ---

    public function test_admin_can_list_bids(): void
    {
        $user    = User::factory()->create();
        $auction = Auction::factory()->create();
        Bid::factory()->count(4)->create(['user_id' => $user->id, 'auction_id' => $auction->id]);

        $this->actingAs($this->admin())->get('/admin/bids')->assertOk();
    }

    // --- User management ---

    public function test_admin_can_view_users(): void
    {
        $this->actingAs($this->admin())->get('/admin/users')->assertOk();
    }

    public function test_admin_can_approve_user(): void
    {
        $user      = User::factory()->unapproved()->create();

        $this->actingAs($this->admin())
            ->patch("/admin/users/{$user->id}/approve")
            ->assertRedirect();

        $this->assertTrue($user->fresh()->isApproved());
    }

    public function test_admin_can_reject_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($this->admin())
            ->patch("/admin/users/{$user->id}/reject")
            ->assertRedirect();

        $this->assertFalse($user->fresh()->isApproved());
    }
}
