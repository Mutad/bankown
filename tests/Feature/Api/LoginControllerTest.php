<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Log;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginControllerTest extends TestCase
{
    // use DatabaseMigrations;

    /**
     * @group API
     * @return void
     */
    public function testApiLoginEndpointMethod()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get(route('api.auth.login'));

        $response->assertStatus(405);
    }

    /**
     * @group API
     * @return void
     */
    public function testLoginValidation()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post(route('api.auth.login'));
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'email',
                'password'
            ]
        ]);
        $response->assertStatus(422);
    }

    /**
     * @group API
     * @return void
     */
    public function testLoginWithWrongCredentials()
    {
        $user = factory('App\User')->create();

        // password
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post(route('api.auth.login'), [
            'email' => 'john.doe@notexist.com',
            'password' => 'ohhellyeah',
        ]);
        $response->assertStatus(401);
        $response->assertJsonStructure([
            'error'
        ]);
    }

    /**
     * @group API
     * @return void
     */
    public function testLoginSuccess()
    {
        $user = factory('App\User')->create();

        // password
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post(route('api.auth.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token_type',
            'access_token',
            'expires_at',
            'user' => [
                'id',
                'first_name',
                'last_name',
                'country',
                'birth_date',
                'email'
            ]
        ]);
    }
}