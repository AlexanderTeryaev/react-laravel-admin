<?php

namespace Tests\Feature;

use App\Author;
use App\Category;
use App\Quizz;
use App\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    private $headers = ["x-mrmld-device-id" => "test-device-id"];

    /**
     * Test user create category
     *
     * @return void
     */
    public function testCreateCategory()
    {
        $user = factory(User::class)->states( 'verified')->create(['current_group_portal' => 1]);
        $user->addInGroupManagers(1);
        $token = Auth::tokenById($user->id);

        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->mutation('createCategory', ['input' => ['name' => 'test_category']], ['name'])
            ->assertJsonFragment([
                'name' => 'test_category'
            ]);
    }

    /**
     * Test user update category
     *
     * @return void
     */
    public function testUpdateCategory()
    {
        $user = factory(User::class)->states( 'verified')->create(['current_group_portal' => 1]);
        $user->addInGroupManagers(1);
        $token = Auth::tokenById($user->id);

        $category = factory(Category::class)->create(['group_id' => 1]);

        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->mutation('updateCategory', ['category_id' => $category->id, 'input' => ['name' => 'test_category']], ['id', 'name'])
            ->assertJsonFragment([
                'id' => strval($category->id),
                'name' => 'test_category'
            ]);
    }

    /**
     * Test user delete category
     *
     * @return void
     */
    public function testDeleteCategory()
    {
        $user = factory(User::class)->states( 'verified')->create(['current_group_portal' => 1]);
        $user->addInGroupManagers(1);
        $token = Auth::tokenById($user->id);

        $category = factory(Category::class)->create(['group_id' => 1]);

        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->mutation('deleteCategory', ['category_id' => $category->id], ['id'])
            ->assertJson([
                'data' => [
                    'deleteCategory' => [
                        'id' => $category->id
                    ]
                ]
            ]);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id
        ]);
    }

    /**
     * Test user get categories
     *
     * @return void
     */
    public function testGetCategories()
    {
        $categories = factory(Category::class, 10)->create(['group_id' => 1]);

        $categories_formatted = collect();
        foreach ($categories as $category)
            $categories_formatted->push([
                'id' => strval($category->id),
                'name' => $category->name,
                'logo_url' => $category->logo_url,
                'created_at' => $category->created_at->toDateTimeString()
            ]);

        $this->withHeaders($this->headers)
            ->query('categories', ['data' => ['id', 'name', 'logo_url', 'created_at']])
            ->assertJson([
                'data' => [
                    'categories' => [
                        'data' => $categories_formatted->toArray()
                    ]
                ]
            ]);
    }

    /**
     * Test user get category
     *
     * @return void
     */
    public function testGetCategory()
    {
        $category = factory(Category::class)->create(['group_id' => 1]);

        $this->withHeaders($this->headers)
            ->query('category', ['id' => $category->id], ['id', 'name', 'logo_url', 'created_at'])
            ->assertJsonFragment([
               'id' => strval($category->id),
               'name' => $category->name,
               'logo_url' => $category->logo_url,
               'created_at' => $category->created_at->toDateTimeString(),
            ]);
    }

    /**
     * Test user subscribe to category quizzes
     *
     * @return void
     */
    public function testSubscribeCategoryQuizzes()
    {
        $category = factory(Category::class)->create(['group_id' => 1]);
        $author = factory(Author::class)->create(['group_id' => 1]);
        $quizzes = factory(Quizz::class, 10)->state('published')->create(['group_id' => 1, 'author_id' => $author->id]);
        $category->quizzes()->attach($quizzes->pluck('id'));

        $result = collect();

        foreach ($quizzes as $quiz)
            $result->push([
                'quizz' => [
                    'id' => strval($quiz->id),
                    'name' => $quiz->name
                ]
            ]);

        $this->withHeaders($this->headers)
            ->mutation('subscribeCategoryQuizzes', ['category_id' => $category->id], ['quizz' => ['id', 'name']])
            ->assertJson([
                'data' => [
                    'subscribeCategoryQuizzes' => $result->toArray()
                ]
            ]);

        $this->withHeaders($this->headers)
            ->query('viewer', ['subscriptions' => ['data' => ['quizz' => ['id', 'name']]]])
            ->assertJsonFragment([
                'data' => $result->toArray()
            ]);
    }
}
