<?php

namespace Tests\Feature\Frontend;

use App\Models\Auth\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordExpirationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_is_requested_to_change_their_password_after_it_expires()
    {
        config(['access.users.password_expires_days' => 30]);

        $user = factory(User::class)->create(['password_changed_at' => now()->subMonths(2)->toDateTimeString()]);

        $response = $this->actingAs($user)
            ->get('/dashboard')
            ->assertRedirect('/password/expired');

        $this->assertSame(302, $response->getStatusCode());
    }

    /** @test */
    public function a_user_is_not_requested_to_change_their_password_if_it_not_old_enough()
    {
        config(['access.users.password_expires_days' => 30]);

        $user = factory(User::class)->create(['password_changed_at' => now()->subWeek()->toDateTimeString()]);

        $this->actingAs($user);

        $response = $this->actingAs($user)
            ->get('/dashboard');

        $this->assertSame(200, $response->getStatusCode());
    }

    /** @test */
    public function a_user_is_not_requested_to_change_password_if_expiration_is_off()
    {
        config(['access.users.password_expires_days' => false]);

        $user = factory(User::class)->create(['password_changed_at' => now()->subMonths(2)->toDateTimeString()]);

        $response = $this->actingAs($user)->get('/dashboard');

        $this->assertSame(200, $response->getStatusCode());
    }

    /** @test */
    public function the_password_can_be_validated()
    {
        config(['access.users.password_history' => false]);
        config(['access.users.password_expires_days' => 30]);

        $user = factory(User::class)->create([
            'password' => 'Donnietto@6789',
            'password_changed_at' => now()->subMonths(2)->toDateTimeString(),
        ]);

        $response = $this->actingAs($user)
            ->followingRedirects()
            ->patch('/password/expired', [
                'old_password' => 'Donnietto@6789',
                'password' => 'JohnExample@12345',
                'password_confirmation' => 'JohnExample@12345',
            ]);

        $this->assertStringContainsString('', $response->content());
    }

    /** @test */
    public function a_user_can_use_the_same_password_when_history_is_off_on_password_expiration()
    {
        config(['access.users.password_history' => false]);
        config(['access.users.password_expires_days' => 30]);

        $user = factory(User::class)->create([
            'password' => 'OC4Nzu270N!QBVi%U%qX',
            'password_changed_at' => now()->subMonths(2)->toDateTimeString(),
        ]);

        $response = $this->actingAs($user)
            ->patch('/password/expired', [
                'old_password' => 'OC4Nzu270N!QBVi%U%qX',
                'password' => 'OC4Nzu270N!QBVi%U%qX',
                'password_confirmation' => 'OC4Nzu270N!QBVi%U%qX',
            ]);

        $response->assertSessionHas('flash_success');
        $this->assertTrue(Hash::check('OC4Nzu270N!QBVi%U%qX', $user->fresh()->password));
    }

    /** @test */
    public function a_user_can_not_use_the_same_password_when_history_is_on_on_password_expiration()
    {
        config(['access.users.password_history' => 3]);
        config(['access.users.password_expires_days' => 30]);

        $user = factory(User::class)->create([
            'password' => 'JohnExample@12345',
            'password_changed_at' => now()->subMonths(2)->toDateTimeString(),
        ]);

        $this->actingAs($user)
            ->patch('/password/expired', [
                'old_password' => 'JohnExample@12345',
                'password' => ':Donnietto@6789',
                'password_confirmation' => ':Donnietto@6789',
            ]);

        $response = $this->actingAs($user)
            ->patch('/password/expired', [
                'old_password' => ':Donnietto@6789',
                'password' => 'JohnExample@12345',
                'password_confirmation' => 'JohnExample@12345',
            ]);

        $response->assertSessionHasErrors();
        $errors = session('errors');
        $this->assertSame($errors->get('password')[0], __('auth.password_used'));
        $this->assertTrue(Hash::check(':Donnietto@6789', $user->fresh()->password));
    }
}
