<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEmailsList()
    {
        $response = $this->getJson(route('emails.list'));

        $response->assertStatus(200);
    }

    public function testNewEmail()
    {
        $response = $this->postJson(route('emails.new'), [
            'subject' => 'asdf',
            'sender' => 'sender@email.com',
            'recipient' => 'recipient@email.com',
            'text' => '<h1>Hi</h1>'
        ]);

        $response->assertStatus(201);
        $emailId = $response->getData()->email->id;

        $response = $this->getJson(route('emails.view', ['email' => $emailId]));
        $response->assertStatus(200);
    }
}
