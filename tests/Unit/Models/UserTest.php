<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_is_admin_returns_correct_value(): void
    {
        $admin = User::factory()->admin()->create();
        $user  = User::factory()->create();

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($user->isAdmin());
    }

    public function test_is_approved_returns_correct_value(): void
    {
        $approved   = User::factory()->create();
        $unapproved = User::factory()->unapproved()->create();

        $this->assertTrue($approved->isApproved());
        $this->assertFalse($unapproved->isApproved());
    }

    public function test_user_has_bids_relationship(): void
    {
        $user    = User::factory()->create();
        $auction = \App\Models\Auction::factory()->create();

        \App\Models\Bid::factory()->create(['user_id' => $user->id, 'auction_id' => $auction->id]);
        \App\Models\Bid::factory()->create(['user_id' => $user->id, 'auction_id' => $auction->id]);

        $this->assertCount(2, $user->bids);
    }

    public function test_user_has_legal_form_relationship(): void
    {
        $user = User::factory()->create(['form_id' => 1]);

        $this->assertNotNull($user->legalForm);
        $this->assertEquals('ООО', $user->legalForm->title);
    }
}
