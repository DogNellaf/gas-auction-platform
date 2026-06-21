<?php

namespace Tests\Feature;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\User;
use Tests\TestCase;

class HomeTest extends TestCase
{
    public function test_guest_is_redirected_from_home(): void
    {
        $this->get('/home')->assertRedirect('/login');
    }

    public function test_admin_is_redirected_to_admin_panel(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/home')->assertRedirect('/admin');
    }

    public function test_user_sees_their_bids(): void
    {
        $user    = User::factory()->create();
        $auction = Auction::factory()->create();
        Bid::factory()->count(3)->create(['user_id' => $user->id, 'auction_id' => $auction->id]);

        $response = $this->actingAs($user)->get('/home');

        $response->assertOk();
        $this->assertCount(3, $response->viewData('bids'));
    }

    public function test_user_can_view_profile_editor(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/home/profile')->assertOk();
    }

    public function test_user_can_update_profile(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch('/home/profile', [
            'name'         => 'newlogin',
            'email'        => 'new@example.com',
            'company_name' => 'New Company',
            'form_id'      => 1,
            'phone'        => '+79998887766',
        ]);

        $response->assertRedirect('/home/profile');
        $this->assertDatabaseHas('users', ['email' => 'new@example.com', 'name' => 'newlogin']);
    }

    public function test_profile_update_rejects_duplicate_email(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch('/home/profile', [
            'name'         => $user->name,
            'email'        => 'taken@example.com',
            'company_name' => $user->company_name,
            'form_id'      => 1,
            'phone'        => $user->phone,
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_profile_update_allows_same_email(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch('/home/profile', [
            'name'         => $user->name,
            'email'        => $user->email,
            'company_name' => $user->company_name,
            'form_id'      => 1,
            'phone'        => $user->phone,
        ]);

        $response->assertRedirect('/home/profile');
    }
}
