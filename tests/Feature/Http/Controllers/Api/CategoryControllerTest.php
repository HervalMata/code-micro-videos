<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;
use Tests\Traits\TestValidations;

class CategoryControllerTest extends TestCase
{
    use DatabaseMigrations;
    use TestValidations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $categories = factory(Category::class)->create();
        $response = $this->get(route('categories.index'));

        $response->assertStatus(200)
            ->assertJson([$categories->toArray()]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testShow()
    {
        $category = factory(Category::class)->create();
        $response = $this->get(route('categories.show', ['category' => $category->id]));

        $response->assertStatus(200)
            ->assertJson($category->toArray());
    }

    public function testInvalidationRulePost()
    {
        $response = $this->json('POST', route('categories.store'), []);

        $this->assertInvalidationNameRequired($response);

        $response = $this->json('POST', route('categories.store'), [
            'name' => str_repeat('a', 256),
            'is_active' => 'a'
        ]);

        $this->assertInvalidationNameMax($response);
        $this->assertInvalidationIsActiveBoolean($response);
    }

    protected function assertInvalidationNameRequired(TestResponse $response)
    {
        $this->assertInvalidationFields($response, ['name'], 'required');
        $response->assertJsonMissingValidationErrors(['is_active']);
    }

    protected function assertInvalidationNameMax(TestResponse $response)
    {
        $this->assertInvalidationFields($response, ['name'], 'max.string', ['max' => 255]);
    }

    protected function assertInvalidationIsActiveBoolean(TestResponse $response)
    {
        $this->assertInvalidationFields($response, ['is_active'], 'boolean');
    }

    public function testStore()
    {
        $response = $this->json('POST', route('categories.store'), [
            'name' => 'test'
        ]);

        $id = $response->json('id');
        $category = Category::find($id);

        $response
            ->assertStatus(201)
            ->assertJson($category->toArray());

        $this->assertTrue($response->json('is_active'));
        $this->assertNull($response->json('description'));

        $response = $this->json('POST', route('categories.store'), [
            'name' => 'test',
            'description' => 'Test Description',
            'is_active' => false
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonFragment([
                'description' => 'Test Description',
                'is_active' => false
            ]);
    }

    public function testUpdate()
    {
        $category = factory(Category::class)->create([
            'description' => 'Test Description',
            'is_active' => false
        ]);

        $response = $this->json('PUT', route('categories.update', ['category' => $category->id]), [
            'name' => 'test',
            'description' => 'Test Update Description',
            'is_active' => true
        ]);

        $id = $response->json('id');
        $category = Category::find($id);

        $response
            ->assertStatus(200)
            ->assertJson($category->toArray())
            ->assertJsonFragment([
                'name' => 'test',
                'description' => 'Test Update Description',
                'is_active' => true
            ]);

        $response = $this->json('PUT', route('categories.update', ['category' => $category->id]), [
            'name' => 'test',
            'description' => '',
            'is_active' => true
        ]);

        $response
            ->assertJsonFragment([
                'description' => null
            ]);
    }

    public function testDestroy()
    {
        $category = factory(Category::class)->create();

        $response = $this->json('DELETE', route('categories.destroy', ['category' => $category->id]));

        $response
            ->assertStatus(204);

        $this->assertEmpty($response->getContent());
    }

    public function testInvalidationRulePut()
    {
        $category = factory(Category::class)->create();
        $response = $this->json('PUT', route('categories.update', ['category' => $category->id]), []);

        $this->assertInvalidationNameRequired($response);

        $response = $this->json('PUT', route('categories.update', ['category' => $category->id]), [
            'name' => str_repeat('a', 256),
            'is_active' => 'a'
        ]);

        $this->assertInvalidationNameMax($response);
        $this->assertInvalidationIsActiveBoolean($response);
    }
}
