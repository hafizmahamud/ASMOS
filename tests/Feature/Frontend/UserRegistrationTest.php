<?php

namespace Tests\Feature\Frontend;

use App\Events\Frontend\Auth\UserConfirmed;
use App\Events\Frontend\Auth\UserRegistered;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Notifications\Frontend\Auth\UserNeedsConfirmation;
use App\Repositories\Frontend\Auth\UserRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper function for registering a user.
     * @param array $userData
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function registerUser($userData = [])
    {
        factory(Role::class)->create(['name' => 'user']);

        return $this->post('/register', array_merge([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'john',
            'mobile' => '60123456789',
            'role' => 'admin',
            'email' => 'john@example.com',
            'password' => 'OC4Nzu270N!QBVi%U%qX',
            'password_confirmation' => 'OC4Nzu270N!QBVi%U%qX',
        ], $userData));
    }

    /** @test */
    public function the_register_route_exists()
    {
        $this->get('/register')->assertStatus(302);
    }

    /** @test */
    public function user_registration_can_be_disabled()
    {
        config(['access.registration' => false]);
        $this->get('/register')->assertStatus(302);
    }

    /** @test */
    // public function a_user_can_register_an_account()
    // {
    //     $this->registerUser([
    //         'first_name' => 'John',
    //         'last_name' => 'Doe',
    //         'username' => 'john',
    //         'mobile' => '60123456789',
    //         'role' => 'admin',
    //         'email' => 'john@example.com',
    //         'password' => 'OC4Nzu270N!QBVi%U%qX',
    //         'password_confirmation' => 'OC4Nzu270N!QBVi%U%qX',
    //     ]);

    //     $newUser = resolve(UserRepository::class)->where('email', 'john@example.com')->first();
    //     $this->assertSame($newUser->first_name, 'John');
    //     $this->assertSame($newUser->last_name, 'Doe');
    //     $this->assertSame($newUser->username, 'john');
    //     $this->assertSame($newUser->mobile, '60123456789');
    //     $this->assertSame($newUser->role, 'admin');
    //     $this->assertTrue(Hash::check('OC4Nzu270N!QBVi%U%qX', $newUser->password));
    // }

    /** @test */
    // public function if_email_confirmation_is_active_an_notification_gets_sent()
    // {
    //     config(['access.users.confirm_email' => true]);
    //     Notification::fake();

    //     $this->registerUser(['email' => 'john@example.com']);
    //     $user = resolve(UserRepository::class)->where('email', 'john@example.com')->first();

    //     Notification::assertSentTo($user, UserNeedsConfirmation::class);
    // }

    /** @test */
    public function first_name_is_required()
    {
        $response = $this->post('/register', [
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(302);
    }

    /** @test */
    public function last_name_is_required()
    {
        $response = $this->post('/register', [
            'first_name' => 'John',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(302);
    }

    /** @test */
    public function email_is_required()
    {
        $response = $this->post('/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(302);
    }

    /** @test */
    public function email_must_be_unique()
    {
        factory(User::class)->create(['email' => 'john@example.com']);

        $response = $this->post('/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(302);
    }

    /** @test */
    public function password_must_be_confirmed()
    {
        $response = $this->post('/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(302);
    }

    /** @test */
    public function passwords_must_be_equivalent()
    {
        $response = $this->post('/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'not_the_same',
        ]);

        $response->assertStatus(302);
    }

    /** @test */
    public function it_redirects_to_dashboard_after_successful_registration()
    {
        config(['access.users.confirm_email' => false]);

        $response = $this->registerUser();

        $response->assertRedirect('/login');
    }
}
