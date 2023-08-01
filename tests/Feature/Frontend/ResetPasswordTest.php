<?php

namespace Tests\Feature\Frontend;

use App\Models\Auth\User;
use App\Notifications\Frontend\Auth\UserNeedsPasswordReset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_password_reset_route_exists()
    {
        $this->get('password/reset')->assertStatus(200);
    }

    /** @test */
    public function email_is_required_in_email_password_form()
    {
        $response = $this->post('/password/reset', ['email' => '']);
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function a_notification_gets_sent_if_password_reset_is_requested()
    {
        $user = factory(User::class)->create(['email' => 'john@example.com']);
        Notification::fake();

        $this->post('password/email', ['email' => 'john@example.com']);

        Notification::assertSentTo($user, UserNeedsPasswordReset::class);
    }

    /** @test */
    public function the_reset_password_form_has_required_fields()
    {
        $response = $this->post('password/reset', [
            'token' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors(['token', 'email', 'password']);
    }

    /** @test */
    public function a_password_can_be_reset()
    {
        $user = factory(User::class)->create(['email' => 'john@example.com']);
        $token = $this->app->make('auth.password.broker')->createToken($user);

        $this->post('password/reset', [
            'token' => $token,
            'email' => 'john@example.com',
            'password' => 'JohnExample@12345',
            'password_confirmation' => 'JohnExample@12345',
        ]);

        $this->assertTrue(Hash::check('JohnExample@12345', $user->fresh()->password));
    }

    /** @test */
    public function the_password_can_be_validated()
    {
        $user = factory(User::class)->create(['email' => 'john@example.com']);
        $token = $this->app->make('auth.password.broker')->createToken($user);

        $response = $this->followingRedirects('password/reset')
            ->post('password/reset', [
                'token' => $token,
                'email' => 'john@example.com',
                'password' => 'JohnExample@12345',
                'password_confirmation' => 'JohnExample@12345',
            ]);

        $this->assertStringContainsString('', $response->content());
    }

    /** @test */
    public function a_user_can_use_the_same_password_when_history_is_off_on_password_reset()
    {
        config(['access.users.password_history' => false]);

        $user = factory(User::class)->create(['email' => 'john@example.com', 'password' => ']EqZL4}zBT']);
        $token = $this->app->make('auth.password.broker')->createToken($user);

        $response = $this->post('password/reset', [
            'token' => $token,
            'email' => 'john@example.com',
            'password' => 'JohnExample@12345',
            'password_confirmation' => 'JohnExample@12345',
        ]);

        $response->assertSessionHas('flash_success');
        $this->assertTrue(Hash::check('JohnExample@12345', $user->fresh()->password));
    }

    /** @test */
    public function a_user_can_not_use_the_same_password_when_history_is_on_on_password_reset()
    {
        config(['access.users.password_history' => 3]);

        $user = factory(User::class)->create(['email' => 'john@example.com', 'password' => 'JohnExample@12345']);

        // Change once
        $this->actingAs($user)
            ->patch('/password/update', [
                'old_password' => 'JohnExample@12345',
                'password' => 'Donnietto@6789',
                'password_confirmation' => 'Donnietto@6789',
            ]);

        $this->assertTrue(Hash::check('Donnietto@6789', $user->fresh()->password));

        auth()->logout();

        $token = $this->app->make('auth.password.broker')->createToken($user);
        $response = $this->post('password/reset', [
            'token' => $token,
            'email' => 'john@example.com',
            'password' => 'JohnExample@12345',
            'password_confirmation' => 'JohnExample@12345',
        ]);

        $response->assertSessionHasErrors();
        $errors = session('errors');
        $this->assertSame($errors->get('password')[0], __('auth.password_used'));
        $this->assertTrue(Hash::check('Donnietto@6789', $user->fresh()->password));
    }
}
