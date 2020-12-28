<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Log;
use Carbon\Carbon;

class RegisterControllerTest extends TestCase
{
    /**
     * @group API
     * @return void
     */
    public function testRegisterEndpointMethod()
    {
        $response = $this->get(route('api.auth.register'));

        $response->assertStatus(405);
    }

    /**
     * @group API
     * @return void
     */
    public function testRegisterValidation()
    {
        $response = $this->withHeader('Accept', 'application/json')
            ->post(route('api.auth.register'));
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
    public function testRegister()
    {
        $response = $this->withHeader('Accept', 'application/json')
            ->post(route('api.auth.register'));
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'first_name',
                'last_name',
                'country',
                'birth_date',
                'email',
                'password',
                'password_repeat'
            ]
        ]);
        $response->assertStatus(422);
    }

    /**
     * @group API
     * @return void
     */
    public function testRegisterSuccess()
    {
        $user = factory('App\User')->make();
        // password
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post(route('api.auth.register'), [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'country' => $user->country,
            'birth_date' => $user->birth_date->format('d/m/Y'),
            'email' => $user->email,
            'password' => 'password',
            'password_repeat' => 'password'
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'access_token',
            'expires_at',
            "user" => [
                "first_name",
                "last_name",
                "country",
                "birth_date",
                "email",
                "updated_at",
                "created_at",
                "id",
            ],
        ]);
        $response->assertJson([
            "user" => [
                "first_name" => $user->first_name,
                "last_name" => $user->last_name,
                "country" => $user->country,
                "birth_date" => $user->birth_date->format('Y-m-d') . 'T00:00:00.000000Z',
                "email" => $user->email,
            ]
        ]);
    }

    /**
     * @group API
     * @return void
     */
    public function testDateFormatValidation()
    {
        // full date format
        $user = factory('App\User')->make();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post(route('api.auth.register'), [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'country' => $user->country,
            'birth_date' => '30/05/1990',
            'email' => $user->email,
            'password' => 'password',
            'password_repeat' => 'password'
        ]);
        $response->assertCreated();

        // month without 0 at begining
        $user = factory('App\User')->make();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post(route('api.auth.register'), [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'country' => $user->country,
            'birth_date' => '30/5/1990',
            'email' => $user->email,
            'password' => 'password',
            'password_repeat' => 'password'
        ]);
        $response->assertStatus(422);

        // wrong format
        $user = factory('App\User')->make();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post(route('api.auth.register'), [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'country' => $user->country,
            'birth_date' => '1990-05-30',
            'email' => $user->email,
            'password' => 'password',
            'password_repeat' => 'password'
        ]);
        $response->assertStatus(422);

        // wrong month
        $user = factory('App\User')->make();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post(route('api.auth.register'), [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'country' => $user->country,
            'birth_date' => '13/1/2000',
            'email' => $user->email,
            'password' => 'password',
            'password_repeat' => 'password'
        ]);
        $response->assertStatus(422);
    }
}