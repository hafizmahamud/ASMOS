<?php

namespace Tests\Feature\Frontend;

use App\Models\Auth\User;
use App\Notifications\Frontend\Auth\UserNeedsConfirmation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UpdateUserAccountTest extends TestCase
{
    use RefreshDatabase;

    /**
     * helper method for valid user data with option to override.
     * @param array $userData
     * @return array
     */
    protected function getValidUserData($userData = [])
    {
        return array_merge([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'timezone' => 'UTC',
            'avatar_type' => 'gravatar',
        ], $userData);
    }

    /** @test */
    public function only_authenticated_users_can_access_their_account()
    {
        $this->get('/account')->assertRedirect('/login');
    }

    /** @test */
    public function a_user_can_update_his_profile()
    {
        $user = factory(User::class)->create();
        config(['access.users.change_email' => true]);

        $this->actingAs($user)
            ->patch('/profile/update', $this->getValidUserData([
                'first_name' => 'John',
                'last_name' => 'Doe',
                'username' => 'john',
                'email' => 'john@example.com',
                'mobile' => '60123456789',
                'role' => 'admin',
            ]));
        $user = $user->fresh();

        $this->assertSame($user->first_name, 'John');
        $this->assertSame($user->last_name, 'Doe');
        $this->assertSame($user->email, 'john@example.com');
        $this->assertSame($user->username, 'john');
        $this->assertSame($user->mobile, '60123456789');
        $this->assertSame($user->role, 'admin');
    }

    /** @test */
    public function the_email_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
            ->patch('/profile/update', $this->getValidUserData(['email' => '']));

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function the_first_name_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
            ->patch('/profile/update', $this->getValidUserData(['first_name' => '']));

        $response->assertSessionHasErrors(['first_name']);
    }

    /** @test */
    public function the_last_name_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
            ->patch('/profile/update', $this->getValidUserData(['last_name' => '']));

        $response->assertSessionHasErrors(['last_name']);
    }

    /** @test */
    public function the_email_needs_to_be_confirmed_if_confirm_email_is_true()
    {
        $user = factory(User::class)->create();
        config(['access.users.confirm_email' => true]);
        config(['access.users.change_email' => true]);
        Notification::fake();

        $this->assertSame($user->confirmed, true);

        $this->actingAs($user)
            ->patch('/profile/update', $this->getValidUserData());

        $this->assertSame($user->fresh()->confirmed, true);
    }

    /** @test */
    public function the_email_needs_not_to_be_confirmed_if_confirm_email_is_false()
    {
        $user = factory(User::class)->create();
        config(['access.users.confirm_email' => false]);

        $this->assertSame($user->confirmed, true);

        $this->actingAs($user)
            ->patch('/profile/update', $this->getValidUserData());

        $this->assertSame($user->fresh()->confirmed, true);
    }
}
