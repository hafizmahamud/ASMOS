<?php

namespace Tests\Feature\Frontend;

use App\Models\Auth\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_password_can_be_validated()
    {
        $user = factory(User::class)->create(['password' => '1234']);

        $response = $this->actingAs($user)
            ->followingRedirects()
            ->patch('/password/update', [
                'old_password' => '1234',
                'password' => '12345678',
                'password_confirmation' => '12345678',
            ]);

        $this->assertStringContainsString('', $response->content());
    }

    /** @test */
    public function the_password_can_be_changed()
    {
        $user = factory(User::class)->create(['password' => '1234']);

        $response = $this->actingAs($user)
            ->patch('/password/update', [
                'old_password' => '1234',
                'password' => 'JohnExample@12345',
                'password_confirmation' => 'JohnExample@12345',
            ]);

        $response->assertSessionHas('flash_success');
        $this->assertTrue(Hash::check('JohnExample@12345', $user->fresh()->password));
    }

    /** @test */
    public function the_password_must_be_confirmed()
    {
        $user = factory(User::class)->create(['password' => '1234']);

        $response = $this->actingAs($user)
            ->patch('/password/update', [
                'old_password' => '1234',
                'password' => 'Boilerplate01',
                'password_confirmation' => '',
            ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function the_old_password_is_required_in_the_password_change_form()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
            ->patch('/password/update', [
                'old_password' => '',
                'password' => 'Boilerplate01',
                'password_confirmation' => 'Boilerplate01',
            ]);

        $response->assertSessionHasErrors('old_password');
    }

    /** @test */
    public function a_user_can_use_the_same_password_when_history_is_off_on_account_change_password()
    {
        config(['access.users.password_history' => false]);

        $user = factory(User::class)->create(['password' => 'JohnExample@12345']);

        $response = $this->actingAs($user)
            ->patch('/password/update', [
                'old_password' => 'JohnExample@12345',
                'password' => 'Donnietto@6789',
                'password_confirmation' => 'Donnietto@6789',
            ]);

        $response->assertSessionHas('flash_success');
        $this->assertTrue(Hash::check('Donnietto@6789', $user->fresh()->password));
    }

    /** @test */
    public function a_user_can_not_use_the_same_password_when_history_is_on_on_account_change_password()
    {
        config(['access.users.password_history' => 3]);

        $user = factory(User::class)->create(['password' => 'JohnExample@12345']);

        // Change once
        $this->actingAs($user)
            ->patch('/password/update', [
                'old_password' => 'JohnExample@12345',
                'password' => 'Donnietto@6789',
                'password_confirmation' => 'Donnietto@6789',
            ]);

        $this->assertTrue(Hash::check('Donnietto@6789', $user->fresh()->password));

        // Change back
        $response = $this->actingAs($user)
            ->patch('/password/update', [
                'old_password' => 'Donnietto@6789',
                'password' => 'JohnExample@12345',
                'password_confirmation' => 'JohnExample@12345',
            ]);

        $response->assertSessionHasErrors();
        $errors = session('errors');
        $this->assertSame($errors->get('password')[0], __('auth.password_used'));
        $this->assertTrue(Hash::check('Donnietto@6789', $user->fresh()->password));
    }

    /** @test */
    public function a_user_can_reuse_a_password_after_it_surpasses_the_limit()
    {
        config(['access.users.password_history' => 2]);

        $user = factory(User::class)->create(['password' => 'JohnExample@12345']);

        // Change once
        $this->actingAs($user)
            ->patch('/password/update', [
                'old_password' => 'JohnExample@12345',
                'password' => 'Donnietto@6789',
                'password_confirmation' => 'Donnietto@6789',
            ]);

        $this->assertTrue(Hash::check('Donnietto@6789', $user->fresh()->password));

        // Change twice
        $this->actingAs($user)
            ->patch('/password/update', [
                'old_password' => 'Donnietto@6789',
                'password' => 'Jiminieo@4567',
                'password_confirmation' => 'Jiminieo@4567',
            ]);

        $response = $this->actingAs($user)
            ->patch('/password/update', [
                'old_password' => 'Jiminieo@4567',
                'password' => 'JohnExample@12345',
                'password_confirmation' => 'JohnExample@12345',
            ]);

        $response->assertSessionHas('flash_success');
        $this->assertTrue(Hash::check('JohnExample@12345', $user->fresh()->password));
    }
}
