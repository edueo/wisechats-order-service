<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class OrderTest extends TestCase
{
    //use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install');
    }

    public function test_authenticated_user_can_create_order()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $orderData = [
            'customer_name' => 'João Silva',
            'customer_email' => 'joao@example.com',
            'items' => [
                [
                    'name' => 'Produto 1',
                    'quantity' => 2,
                    'unit_price' => 10.50,
                ],
                [
                    'name' => 'Produto 2',
                    'quantity' => 1,
                    'unit_price' => 25.00,
                ],
            ],
        ];

        $response = $this->postJson('/api/orders', $orderData);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Pedido criado com sucesso',
                 ]);

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'João Silva',
            'customer_email' => 'joao@example.com',
            'total' => 46.00,
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('order_items', [
            'name' => 'Produto 1',
            'quantity' => 2,
            'unit_price' => 10.50,
            'subtotal' => 21.00,
        ]);
    }

    public function test_user_can_list_own_orders()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        
        Passport::actingAs($user);

        $userOrder = Order::factory()->create(['user_id' => $user->id]);
        $otherUserOrder = Order::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->getJson('/api/orders');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                 ]);

        $responseData = $response->json();
        $orderIds = collect($responseData['data']['data'])->pluck('id')->toArray();

        $this->assertContains($userOrder->id, $orderIds);
        $this->assertNotContains($otherUserOrder->id, $orderIds);
    }

    public function test_user_can_view_own_order()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $order = Order::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/orders/{$order->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'id' => $order->id,
                         'customer_name' => $order->customer_name,
                     ],
                 ]);
    }

    public function test_user_cannot_view_other_user_order()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        
        Passport::actingAs($user);

        $otherOrder = Order::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->getJson("/api/orders/{$otherOrder->id}");

        $response->assertStatus(404)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Pedido não encontrado',
                 ]);
    }

    public function test_unauthenticated_user_cannot_access_orders()
    {
        $response = $this->getJson('/api/orders');

        $response->assertStatus(401);
    }
}