<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_transaction()
    {
        $user = User::factory()->create();

        $data = [
            'user_id' => $user->id,
            'transaction_code' => 'TRX123',
            'amount' => 100.00,
            'transaction_type' => 'credit',
            'status' => 'pending',
        ];

        $response = $this->postJson('/api/transactions', $data);

        $response->assertStatus(201)
                 ->assertJson([
                    'data' => [
                        'transaction_code' => 'TRX123',
                        'amount' => 100.00,
                    ]
                 ]);
    }
}
