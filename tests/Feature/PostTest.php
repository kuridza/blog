<?php

test('post list', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);
    $response = $this->get('/posts');

    $response->assertStatus(200);
});

test('post not found', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);
    $response = $this->get('/posts/99999');

    $response->assertStatus(404);
});
