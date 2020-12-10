<?php


namespace Tests\Feature;


use App\Author;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    use RefreshDatabase;

    private $headers = ["x-mrmld-device-id" => "test-device-id"];

    /**
     * Test user create author
     *
     * @return void
     */
    public function testCreateAuthor()
    {
        $user = factory(User::class)->states( 'verified')->create(['current_group_portal' => 1]);
        $user->addInGroupManagers(1);
        $token = Auth::tokenById($user->id);

        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->mutation('createAuthor', [
                'input' => [
                    'name' => 'author_test',
                    'function' => 'author_func',
                    'description' => 'author_test_desc'
                ]
            ], ['name', 'function', 'description'])
            ->assertJsonFragment([
                'name' => 'author_test',
                'function' => 'author_func',
                'description' => 'author_test_desc'
            ]);
    }

    /**
     * Test user update author
     *
     * @return void
     */
    public function testUpdateAuthor()
    {
        $user = factory(User::class)->states( 'verified')->create(['current_group_portal' => 1]);
        $user->addInGroupManagers(1);
        $token = Auth::tokenById($user->id);

        $author = factory(Author::class)->create(['group_id' => 1]);

        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->mutation('updateAuthor', [
                'author_id' => $author->id,
                'input' => [
                    'name' => 'author_test_update',
                    'function' => 'author_func_update',
                    'description' => 'author_test_desc_update'
                ]
            ], ['name', 'description', 'function', 'id'])
            ->assertJsonFragment([
                'name' => 'author_test_update',
                'function' => 'author_func_update',
                'description' => 'author_test_desc_update'
            ]);

        $this->assertDatabaseHas('authors', [
            'id' => $author->id,
            'name' => 'author_test_update',
            'function' => 'author_func_update',
            'description' => 'author_test_desc_update'
        ]);

    }

    /**
     * Test user delete author
     *
     * @return void
     */
    public function testDeleteAuthor()
    {
        $user = factory(User::class)->states( 'verified')->create(['current_group_portal' => 1]);
        $user->addInGroupManagers(1);
        $token = Auth::tokenById($user->id);

        $author = factory(Author::class)->create(['group_id' => 1]);

        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->mutation('deleteAuthor', ['author_id' => $author->id], ['id'])
            ->assertJson([
                'data' => [
                    'deleteAuthor' => [
                        'id' => $author->id
                    ]
                ]
            ]);

        $this->assertDatabaseMissing('authors', [
            'id' => $author->id
        ]);
    }

    /**
     * Test user get author
     *
     * @return void
     */
    public function testGetAuthors()
    {
        factory(Author::class, 8)->create(['group_id' => 1]);
        $authors_formatted = collect();

        foreach (Author::all() as $author) {
            $authors_formatted->push([
                'id' => strval($author->id),
                'name' => $author->name,
                'pic_url' => $author->pic_url,
                'created_at' => $author->created_at->toDateTimeString(),
            ]);
        }

        $this->withHeaders($this->headers)
            ->query('authors', ['data' => ['id', 'name', 'pic_url', 'created_at']])
            ->assertJson([
                'data' => [
                    'authors' => [
                        'data' => $authors_formatted->toArray()
                    ]
                ]
            ]);
    }

    /**
     * Test user get author
     *
     * @return void
     */
    public function testGetAuthor()
    {
        $auth = factory(Author::class)->create(['group_id' => 1]);

        $this->withHeaders($this->headers)
            ->query('author', ['id' => $auth->id], ['id', 'name', 'pic_url', 'created_at'])
            ->assertJsonFragment([
                'id' => strval($auth->id),
                'name' => $auth->name,
                'pic_url' => $auth->pic_url,
                'created_at' => $auth->created_at->toDateTimeString(),
            ]);
    }
}