<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    private function validData(array $overrides = []): array
    {
        return array_merge([
            'name'                  => 'testuser',
            'email'                 => 'test@example.com',
            'company_name'          => 'Test Corp',
            'form_id'               => 1,
            'phone'                 => '+71234567890',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ], $overrides);
    }

    public function test_registration_creates_unapproved_user(): void
    {
        $this->post('/register', $this->validData())->assertRedirect('/login');

        $user = User::where('email', 'test@example.com')->first();

        $this->assertNotNull($user);
        $this->assertFalse($user->isApproved());
        $this->assertFalse($user->isAdmin());
    }

    public function test_duplicate_email_is_rejected(): void
    {
        User::factory()->create(['email' => 'test@example.com']);

        $this->post('/register', $this->validData())
            ->assertSessionHasErrors('email');
    }

    public function test_duplicate_company_name_is_rejected(): void
    {
        User::factory()->create(['company_name' => 'Test Corp']);

        $this->post('/register', $this->validData())
            ->assertSessionHasErrors('company_name');
    }

    public function test_password_confirmation_is_required(): void
    {
        $this->post('/register', $this->validData(['password_confirmation' => 'different']))
            ->assertSessionHasErrors('password');
    }

    public function test_invalid_form_id_is_rejected(): void
    {
        $this->post('/register', $this->validData(['form_id' => 999]))
            ->assertSessionHasErrors('form_id');
    }

    public function test_registration_page_is_accessible(): void
    {
        $this->get('/register')->assertOk();
    }
}
