<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'kasir']);
    $this->category = Category::factory()->create(['name' => 'Makanan', 'slug' => 'makanan']);
    $this->product = Product::factory()->create([
        'category_id' => $this->category->id,
        'name' => 'Nasi Goreng',
        'price' => 15000,
        'stock' => 10,
        'is_active' => true,
    ]);
});

// --- Scenarios ---

it('processes a valid cash transaction', function () {
    $response = $this->actingAs($this->user)->postJson('/kasir/transaksi', [
        'items_json' => json_encode([
            ['id' => $this->product->id, 'name' => $this->product->name, 'price' => 15000, 'qty' => 2, 'maxStock' => 10],
        ]),
        'paid_amount' => 50000,
        'payment_method' => 'cash',
        'discount' => 0,
        'notes' => '',
    ]);

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('transaction.paid_fmt', '50.000')
        ->assertJsonPath('transaction.change_fmt', '20.000');

    // Stock decreased
    expect($this->product->fresh()->stock)->toBe(8);

    // Activity log recorded
    expect(Transaction::count())->toBe(1);
});

it('rejects payment less than total', function () {
    $response = $this->actingAs($this->user)->postJson('/kasir/transaksi', [
        'items_json' => json_encode([
            ['id' => $this->product->id, 'name' => $this->product->name, 'price' => 15000, 'qty' => 3, 'maxStock' => 10],
        ]),
        'paid_amount' => 20000, // total is 45000
        'payment_method' => 'cash',
        'discount' => 0,
        'notes' => '',
    ]);

    $response->assertStatus(422)
        ->assertJsonPath('success', false);
});

it('processes a QRIS transaction', function () {
    $response = $this->actingAs($this->user)->postJson('/kasir/transaksi', [
        'items_json' => json_encode([
            ['id' => $this->product->id, 'name' => $this->product->name, 'price' => 15000, 'qty' => 1, 'maxStock' => 10],
        ]),
        'paid_amount' => 0,
        'payment_method' => 'qris',
        'discount' => 0,
        'notes' => '',
    ]);

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('transaction.payment_method', 'qris')
        ->assertJsonPath('transaction.change_fmt', '0');
});

it('applies discount correctly', function () {
    $response = $this->actingAs($this->user)->postJson('/kasir/transaksi', [
        'items_json' => json_encode([
            ['id' => $this->product->id, 'name' => $this->product->name, 'price' => 15000, 'qty' => 2, 'maxStock' => 10],
        ]),
        'paid_amount' => 30000,
        'payment_method' => 'cash',
        'discount' => 5000,
        'notes' => '',
    ]);

    $response->assertOk()
        ->assertJsonPath('success', true);
    // total = 30000 - 5000 = 25000, paid 30000 → change 5000
    expect($response->json('transaction.change_fmt'))->toBe('5.000');
});

it('rejects empty cart', function () {
    $response = $this->actingAs($this->user)->postJson('/kasir/transaksi', [
        'items_json' => json_encode([]),
        'paid_amount' => 0,
        'payment_method' => 'cash',
        'discount' => 0,
        'notes' => '',
    ]);

    $response->assertStatus(422)
        ->assertJsonPath('success', false);
});

it('rejects when stock insufficient', function () {
    $this->product->update(['stock' => 2]);

    $response = $this->actingAs($this->user)->postJson('/kasir/transaksi', [
        'items_json' => json_encode([
            ['id' => $this->product->id, 'name' => $this->product->name, 'price' => 15000, 'qty' => 5, 'maxStock' => 2],
        ]),
        'paid_amount' => 100000,
        'payment_method' => 'cash',
        'discount' => 0,
        'notes' => '',
    ]);

    $response->assertStatus(422)
        ->assertJsonPath('success', false);

    // Stock must NOT change
    expect($this->product->fresh()->stock)->toBe(2);
});

it('redirects unauthenticated user to login', function () {
    $response = $this->post('/kasir/transaksi', [
        'items_json' => json_encode([
            ['id' => 1, 'name' => 'X', 'price' => 1000, 'qty' => 1, 'maxStock' => 5],
        ]),
        'paid_amount' => 5000,
        'payment_method' => 'cash',
        'discount' => 0,
        'notes' => '',
    ]);

    $response->assertRedirect('/login');
});
