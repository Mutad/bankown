<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Log;

class CardControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     * @group API
     * @return void
     */
    public function testAllCardsAcess()
    {
        $response = $this->withHeader('Accept', 'application/json')
            ->get(route('api.cards.index'));

        $response->assertStatus(401);

        $card = factory('App\Card')->create();

        $response = $this->actingAs($card->owner, 'api')
            ->withHeader('Accept', 'application/json')
            ->get(route('api.cards.index'));
    }

    /**
     * A basic feature test example.
     * @group API
     * @return void
     */
    public function testCardToUserRelation()
    {
        $card = factory('App\Card')->create();

        $response = $this->actingAs($card->owner, 'api')
            ->withHeader('Accept', 'application/json')
            ->get(route('api.cards.index'));

        $response->assertJson([
            [
                'id' => $card->id,
                'name' => $card->name,
                'balance' => $card->balance,
                'currency' => $card->currency,
                'user_id' => $card->owner->id,
                'type' => $card->type,
                'number' => $card->number
            ]
        ]);

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     * @group API
     * @return void
     */
    public function testCardCreationFieldsAreRequired()
    {
        $user = factory('App\User')->create();

        $response = $this->actingAs($user, 'api')
            ->withHeader('Accept', 'application/json')
            ->post(route('api.cards.store'), []);

        $response->assertJsonValidationErrors(['name', 'currency', 'type']);
    }

    /**
     * A basic feature test example.
     * @group API
     * @return void
     */
    public function testCardCreation()
    {
        $user = factory('App\User')->create();
        $card = factory('App\Card')->make();

        $response = $this->actingAs($user, 'api')
            ->withHeader('Accept', 'application/json')
            ->post(route('api.cards.store'), [
                'name' => $card->name,
                'currency' => $card->currency,
                'type' => $card->type,
            ]);

        $response->assertCreated();
    }

    /**
     * A basic feature test example.
     * @group API
     * @return void
     */
    public function testGetCardInfo()
    {
        $card = factory('App\Card')->create();

        $response = $this->actingAs($card->owner, 'api')
            ->withHeader('Accept', 'application/json')
            ->get(route('api.cards.info', [$card->number]));

        $response->assertOk();
        $response->assertJsonStructure([
            'currency',
            'owner',
        ]);
    }

    /**
     * A basic feature test example.
     * @group API
     * @return void
     */
    public function testGetCardById()
    {
        $card = factory('App\Card')->create();

        $response = $this->actingAs($card->owner, 'api')
            ->withHeader('Accept', 'application/json')
            ->get(route('api.cards.show', $card->id));

        $response->assertOk();
        $response->assertJson([
            'id' => $card->id,
        ]);

        $user = factory('App\User')->create();
        $response = $this->actingAs($user, 'api')
            ->withHeader('Accept', 'application/json')
            ->get(route('api.cards.show', $card->id));

        $response->assertForbidden();
        $response->assertJsonStructure([
            'error'
        ]);
    }

    /**
     * A basic feature test example.
     * @group API
     * @return void
     */
    public function testUpdateCardValidationRules()
    {
        $card = factory('App\Card')->create();

        $response = $this->actingAs($card->owner, 'api')
            ->withHeader('Accept', 'application/json')
            ->put(route('api.cards.update', [$card->id]), []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    /**
     * A basic feature test example.
     * @group API
     * @return void
     */
    public function testUpdateCardAuthCheck()
    {
        $card = factory('App\Card')->create();

        $user = factory('App\User')->create();

        $response = $this->actingAs($user, 'api')
            ->withHeader('Accept', 'application/json')
            ->put(route('api.cards.update', [$card->id]), [
                'name' => 'Updated name'
            ]);

        $response->assertForbidden();
        $response->assertJsonStructure(['error']);
    }

    /**
     * A basic feature test example.
     * @group API
     * @return void
     */
    public function testUpdateCard()
    {
        $card = factory('App\Card')->create();

        $response = $this->actingAs($card->owner, 'api')
            ->withHeader('Accept', 'application/json')
            ->put(route('api.cards.update', [$card->id]), [
                'name' => 'Updated name'
            ]);

        $response->assertOk();
        $response->assertJson([
            'id' => $card->id,
            'name' => 'Updated name'
        ]);
    }

    /**
     * A basic feature test example.
     * @group API
     * @return void
     */
    public function testDeleteCardAuthCheck()
    {
        $card = factory('App\Card')->create();

        $user = factory('App\User')->create();

        $response = $this->actingAs($user, 'api')
            ->withHeader('Accept', 'application/json')
            ->delete(route('api.cards.destroy', [$card->id]));

        $response->assertForbidden();
        $response->assertJsonStructure(['error']);
    }

    /**
     * A basic feature test example.
     * @group API
     * @return void
     */
    public function testDeleteCard()
    {
        $card = factory('App\Card')->create();

        $response = $this->actingAs($card->owner, 'api')
            ->withHeader('Accept', 'application/json')
            ->delete(route('api.cards.destroy', [$card->id]));

        $response->assertOk();
    }
}