<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StatusControllerTest extends TestCase
{
    /**
     * @group API
     * @return void
     */
    public function testStatusCodeOk()
    {
        $response = $this->get(route('api.status'));

        $response->assertStatus(200);
    }

    /**
     * @group API
     * @return void
     */
    public function testStatusJsonValid()
    {
        $response = $this->get(route('api.status'));

        $response->assertJson([
            'message' => 'ok'
        ]);
    }
}