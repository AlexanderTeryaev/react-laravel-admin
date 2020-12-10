<?php

use App\Author;
use App\Quizz;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class QuizzTest extends TestCase
{
    use RefreshDatabase;

    private $headers = ["x-mrmld-device-id" => "test-device-id"];

    /**
     * Test user create quizz
     *
     * @return void
     */
    public function testCreateQuizz()
    {
        $user = factory(User::class)->states( 'verified')->create(['current_group_portal' => 1]);
        $user->addInGroupManagers(1);
        $token = Auth::tokenById($user->id);

        $author = factory(Author::class)->create(['group_id' => 1]);

        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->mutation('createQuizz', [
                    'input' => [
                        'name' => 'test_quizz',
                        'description' => 'desc_test desc_test',
                        'author_id' => $author->id,
                        'difficulty' => 'EASY',
                        'is_geolocalized' => false
                    ]
                ], ['name', 'description', 'difficulty', 'image_url', 'is_geolocalized'])
            ->assertJsonFragment([
                'name' => 'test_quizz',
                'description' => 'desc_test desc_test',
                'difficulty' => 'EASY',
                'image_url' => null,
                'is_geolocalized' => false
            ]);
    }

    /**
     * Test user update quizz
     *
     * @return void
    */
    public function testUpdateQuizz()
    {
        $user = factory(User::class)->states( 'verified')->create(['current_group_portal' => 1]);
        $user->addInGroupManagers(1);
        $token = Auth::tokenById($user->id);

        $author = factory(Author::class)->create(['group_id' => 1]);
        $quizz = factory(Quizz::class)->states( 'published')->create(['group_id' => 1, 'author_id' => $author->id]);

        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->mutation('updateQuizz', [
                'quizz_id' => $quizz->id,
                'input' => [
                    'name' => 'test_quiz',
                    'description' => 'desc_test desc_test',
                    'author_id' => $author->id,
                    'difficulty' => 'EASY',
                    'is_geolocalized' => false,
                    'is_published' => false
                ]
            ], ['name', 'description', 'difficulty', 'is_geolocalized', 'is_published'])
            ->assertJsonFragment([
                'name' => 'test_quiz',
                'description' => 'desc_test desc_test',
                'difficulty' => 'EASY',
                'is_geolocalized' => false,
                'is_published' => false
            ]);

        $this->assertDatabaseHas('quizzes', [
           'id' => $quizz->id,
            'name' => 'test_quiz',
            'description' => 'desc_test desc_test',
            'difficulty' => 'EASY',
            'is_geolocalized' => false,
            'is_published' => false
        ]);
    }

    /**
     * Test update quizz with upload image
     *
     * @return void
     */
    public function testUpdateQuizzImage(): void
    {
        $user = factory(User::class)->states( 'verified')->create(['current_group_portal' => 1]);
        $user->addInGroupManagers(1);
        $token = Auth::tokenById($user->id);

        $author = factory(Author::class)->create(['group_id' => 1]);
        $quizz = factory(Quizz::class)->states( 'published')->create(['group_id' => 1, 'author_id' => $author->id, 'image_url' => null]);

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->multipartGraphQL([
            'operations' => /* @lang JSON */
                '
                {
                    "query": "mutation Upload($file: Upload!) { updateQuizz(quizz_id: '. $quizz->id .' input: { image: $file }) { image_url } }",
                    "variables": {
                        "file": null
                    }
                }
                ',
            'map' => /* @lang JSON */
                '
                    {
                        "0": ["variables.file"]
                    }
                ',
        ],
            [
                '0' => UploadedFile::fake()->create('image.jpg', 1000),
            ]
        )->decodeResponseJson();

        Storage::assertExists($response['data']['updateQuizz']['image_url']);
    }

    /**
     * Test user delete quizz
     *
     * @return void
     */
    public function testDeleteQuizz()
    {
        $user = factory(User::class)->states( 'verified')->create(['current_group_portal' => 1]);
        $user->addInGroupManagers(1);
        $token = Auth::tokenById($user->id);

        $author = factory(Author::class)->create(['group_id' => 1]);
        $quizz = factory(Quizz::class)->states( 'published')->create(['group_id' => 1, 'author_id' => $author->id]);

        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->mutation('deleteQuizz', ['quizz_id' => $quizz->id], ['id'])
            ->assertJson([
                'data' => [
                    'deleteQuizz' => [
                        'id' => $quizz->id
                    ]
                ]
            ]);

        $this->assertDatabaseMissing('quizzes', [
            'id' => $quizz->id
        ]);
    }

    /**
     * Test user get quizzes
     *
     * @return void
     */
    public function testGetQuizzes()
    {
        $author = factory(Author::class)->create(['group_id' => 1]);
        $quizzes = factory(Quizz::class, 4)->state( 'published')->create(['group_id' => 1, 'author_id' => $author->id]);

        $quizzes_formatted = collect();
        foreach ($quizzes as $quizz)
            $quizzes_formatted->push([
                'id' => strval($quizz->id),
                'name' => $quizz->name,
                'image_url' => $quizz->image_url,
                'created_at' => $quizz->created_at->toDateTimeString()
            ]);

        $this->withHeaders($this->headers)
            ->query('quizzes', ['data' => ['id', 'name', 'image_url', 'created_at']])
            ->assertJson([
                'data' => [
                    'quizzes' => [
                        'data' => $quizzes_formatted->toArray()
                    ]
                ]
            ]);
    }

    /**
     * Test user get quizz
     *
     * @return void
     */
    public function testGetQuizz()
    {
        $author = factory(Author::class)->create(['group_id' => 1]);
        $quizz = factory(Quizz::class)->state('published')->create(['group_id' => 1, 'author_id' => $author->id]);

        $this->withHeaders($this->headers)
            ->query('quizz', ['id' => $quizz->id], ['id', 'name', 'image_url', 'created_at'])
            ->assertJsonFragment([
                'id' => strval($quizz->id),
                'name' => $quizz->name,
                'image_url' => $quizz->image_url,
                'created_at' => $quizz->created_at->toDateTimeString(),
            ]);
    }

    /**
     * Test user get quizz unpublished
     *
     * @return void
     */
    public function testGetQuizzUnpublished()
    {
        $author = factory(Author::class)->create(['group_id' => 1]);
        $quizz = factory(Quizz::class)->state('unpublished')->create(['group_id' => 1, 'author_id' => $author->id]);

        $this->withHeaders($this->headers)
            ->query('quizz', ['id' => $quizz->id], ['id', 'name', 'image_url', 'created_at'])
            ->assertJsonFragment([
                'quizz' => null,
            ]);
    }

    /**
     * Test user subscribe to quizz
     *
     * @return void
     */
    public function testSubscribeQuizz()
    {
        $author = factory(Author::class)->create(['group_id' => 1]);
        $quizz = factory(Quizz::class)->state('published')->create(['group_id' => 1, 'author_id' => $author->id]);

        $this->withHeaders($this->headers)
            ->mutation('subscribeQuizz', ['quizz_id' => $quizz->id], ['quizz' => ['id']])
            ->assertJson([
                'data' => [
                    'subscribeQuizz' => [
                        'quizz' => [
                            'id' => $quizz->id
                        ]
                    ]
                ]
            ]);

        $this->withHeaders($this->headers)
            ->query('viewer', ['subscriptions' => ['data' => ['quizz' => ['id', 'name']]]])
            ->assertJsonFragment([
                'data' => [
                    'viewer' => [
                        'subscriptions' => [
                            'data' => [
                                [
                                    'quizz' => [
                                        'id' => strval($quizz->id),
                                        'name' => $quizz->name
                                    ],
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Test user unsubscribe to quizz
     *
     * @return void
     */
    public function testUnsubscribeQuizz()
    {
        $author = factory(Author::class)->create(['group_id' => 1]);
        $quizz = factory(Quizz::class)->state('published')->create(['group_id' => 1, 'author_id' => $author->id]);

        $this->withHeaders($this->headers)
            ->mutation('subscribeQuizz', ['quizz_id' => $quizz->id], ['quizz' => ['id']]);

        $this->withHeaders($this->headers)
            ->mutation('unsubscribeQuizz', ['quizz_id' => $quizz->id], ['quizz' => ['id']])
            ->assertJson([
                'data' => [
                    'unsubscribeQuizz' => [
                        'quizz' => [
                            'id' => $quizz->id
                        ]
                    ]
                ]
            ]);


        $this->withHeaders($this->headers)
            ->query('viewer', ['subscriptions' => ['data' => ['quizz' => ['id', 'name']]]])
            ->assertJsonFragment([
                'data' => [
                ]
            ]);
    }
}