<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Todo;
use Laravel\Passport\Passport;

class TodoControllerTest extends TestCase
{
    use WithFaker;
    protected $protected = ['users'];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $user = \App\Models\User::factory()->create();
        Passport::actingAs($user);
        
        $todo = Todo::factory()->create([
            'name' => 'Test Todo',
            'user_id' => $user->id,
        ]);
    
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->createToken('test-token')->accessToken,
            'Accept' => 'application/json',
        ])->get('/api/todo');
    
        $response->assertStatus(200);    
        $json = $response->json();  
        
        $this->assertTrue(collect($json)->contains('name', 'Test Todo')); 
    }

    /**
     * Test store method
     *
     * @return void
     */
    public function testStore()
    {
        $user = \App\Models\User::factory()->create();
        Passport::actingAs($user);

        $data = [
            'name' => 'New Test Todo',
            'tasks' => 'task1'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->createToken('test-token')->accessToken,
            'Accept' => 'application/json',
        ])->post('/api/todo', $data); 
        $response->assertStatus(201);

        $json = $response->json();

        $this->assertEquals($data['name'], $json['name']);
        $this->assertEquals($user->id, $json['user_id']);
    }
    /**
     * Test update method
     *
     * @return void
     */
    public function testUpdate()
    {
        $user = \App\Models\User::factory()->create();
        Passport::actingAs($user);

        $todo = Todo::factory()->create([
            'name' => 'Test Todo',
            'user_id' => $user->id,
        ]);

        $data = [
            'name' => 'Updated Test Todo',
            'tasks' => 'Task 1, Task 2',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->createToken('test-token')->accessToken,
            'Accept' => 'application/json',
        ])->put('/api/todo/' . $todo->id, $data);

        $response->assertStatus(200);

        $json = $response->json();

        $this->assertEquals($data['name'], $json['name']);
        $this->assertEquals($user->id, $json['user_id']);
    }

    /**
     * Test show method
     *
     * @return void
     */
    public function testShow()
    {
        $user = \App\Models\User::factory()->create();
        Passport::actingAs($user);

        $todo = Todo::factory()->create([
            'name' => 'Test Todo',
            'user_id' => $user->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->createToken('test-token')->accessToken,
            'Accept' => 'application/json',
        ])->get('/api/todo/' . $todo->id);

        $response->assertStatus(200);

        $json = $response->json();

        $this->assertEquals($todo->name, $json['name']); 
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $user = \App\Models\User::factory()->create();
        Passport::actingAs($user);

        $todo = Todo::factory()->create([
            'name' => 'Test Todo',
            'user_id' => $user->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->createToken('test-token')->accessToken,
            'Accept' => 'application/json',
        ])->delete('/api/todo/' . $todo->id);

        $response->assertStatus(204);

        $this->assertDeleted($todo);
    }
}
