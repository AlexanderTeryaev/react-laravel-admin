<?php


namespace Tests\Feature;


use App\Author;
use App\Question;
use App\Quizz;
use App\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class QuestionTest extends TestCase
{
    use RefreshDatabase;

    private $headers = ["x-mrmld-device-id" => "test-device-id"];

    /**
     * Test user create question
     *
     * @return void
     */
    public function testCreateQuestion()
    {
        $user = factory(User::class)->states( 'verified')->create(['current_group_portal' => 1]);
        $user->addInGroupManagers(1);
        $token = Auth::tokenById($user->id);

        $quizz = factory(Quizz::class)->create(['group_id' => 1, 'author_id' => 1]);

        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->mutation('createQuestion', [
                'useDefaultImage' => false,
                'input' => [
                    'quizz_id' => $quizz->id,
                    'question' => 'test_quizz',
                    'good_answer' => 'false',
                    'bad_answer' => 'true',
                    'difficulty' => 'EASY',
                    'more' => 'more_test'
                ]
            ], ['id', 'question', 'more', 'difficulty', 'bad_answer', 'good_answer', 'bg_url'])
            ->assertJsonFragment([
                'question' => 'test_quizz',
                'more' => 'more_test',
                'difficulty' => 'EASY',
                'good_answer' => 'false',
                'bad_answer' => 'true',
                'bg_url' => null
            ]);
    }

    /**
     * Test user create question using default quizz image
     *
     * @return void
     */
    public function testCreateQuestionWithDefaultImage()
    {
        $user = factory(User::class)->states( 'verified')->create(['current_group_portal' => 1]);
        $user->addInGroupManagers(1);
        $token = Auth::tokenById($user->id);

        $quizz = factory(Quizz::class)->create(['group_id' => 1, 'author_id' => 1]);

        $response = $this->withHeaders(['Authorization' => "Bearer $token"])
            ->mutation('createQuestion', [
                'useDefaultImage' => true,
                'input' => [
                    'quizz_id' => $quizz->id,
                    'question' => 'test_quizz',
                    'good_answer' => 'false',
                    'bad_answer' => 'true',
                    'difficulty' => 'EASY',
                    'more' => 'more_test'
                ]
            ], ['bg_url'])->decodeResponseJson();


        $this->assertEquals($quizz->default_questions_image, $response['data']['createQuestion']['bg_url']);
    }

    /**
     * Test user update question
     *
     * @return void
     */
    public function testUpdateQuestion()
    {
        $user = factory(User::class)->states( 'verified')->create(['current_group_portal' => 1]);
        $user->addInGroupManagers(1);
        $token = Auth::tokenById($user->id);

        $author = factory(Author::class)->create(['group_id' => 1]);
        $quizz = factory(Quizz::class)->create(['group_id' => 1, 'author_id' => $author->id]);
        $question = factory(Question::class)->create(['group_id' => 1, 'quizz_id' => $quizz->id]);

        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->mutation('updateQuestion', [
                'useDefaultImage' => false,
                'question_id' => $question->id,
                'input' => [
                    'question' => 'test_quiz',
                    'more' => 'more_test',
                    'quizz_id' => $quizz->id,
                    'difficulty' => 'EASY',
                    'good_answer' => 'true',
                    'bad_answer' => 'false'
                ]
            ], ['question', 'more', 'difficulty', 'bad_answer', 'good_answer'])
            ->assertJsonFragment([
                'question' => 'test_quiz',
                'more' => 'more_test',
                'difficulty' => 'EASY',
                'good_answer' => 'true',
                'bad_answer' => 'false'
            ]);
    }

    /** WAITING PARTIAL UPDATE
     * Test update question with upload image
     *
     * @return void
     */
//    public function testUpdateQuizzImage(): void
//    {
//        $user = factory(User::class)->states( 'verified')->create(['current_group_portal' => 1]);
//        $user->addInGroupManagers(1);
//        $token = Auth::tokenById($user->id);
//
//        $author = factory(Author::class)->create(['group_id' => 1]);
//        $quizz = factory(Quizz::class)->create(['group_id' => 1, 'author_id' => $author->id]);
//        $question = factory(Question::class)->create(['group_id' => 1, 'quizz_id' => $quizz->id]);
//
//        $response = $this->withHeaders(['Authorization' => "Bearer $token"])->multipartGraphQL([
//            'operations' => /* @lang JSON */
//                '
//                {
//                    "query": "mutation Upload($file: Upload!) { updateQuestion(question_id: '. $question->id .', useDefaultImage: false, input: { bg: $file }) { bg_url } }",
//                    "variables": {
//                        "file": null
//                    }
//                }
//                ',
//            'map' => /* @lang JSON */
//                '
//                    {
//                        "0": ["variables.file"]
//                    }
//                ',
//        ],
//            [
//                '0' => UploadedFile::fake()->create('image.jpg', 1000),
//            ]
//        )->decodeResponseJson();
//
//        Storage::assertExists($response['data']['updateQuestion']['bg_url']);
//    }*/

    /**
     * Test user delete questions
     *
     * @return void
     */
    public function testDeleteQuestion()
    {
        $user = factory(User::class)->states( 'verified')->create(['current_group_portal' => 1]);
        $user->addInGroupManagers(1);
        $token = Auth::tokenById($user->id);

        $author = factory(Author::class)->create(['group_id' => 1]);
        $quizz = factory(Quizz::class)->create(['group_id' => 1, 'author_id' => $author->id]);
        $question = factory(Question::class)->create(['group_id' => 1, 'quizz_id' => $quizz->id]);

        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->mutation('deleteQuestion', ['question_id' => $question->id], ['id'])
            ->assertJson([
                'data' => [
                    'deleteQuestion' => [
                        'id' => $question->id
                    ]
                ]
            ]);

        $this->assertDatabaseMissing('questions', [
            'id' => $question->id
        ]);
    }

    /**
     * Test user get categories
     *
     * @return void
     */
    public function testGetQuestions()
    {
        $author = factory(Author::class)->create(['group_id' => 1]);
        $quizz = factory(Quizz::class)->create(['group_id' => 1, 'author_id' => $author->id]);
        $questions = factory(Question::class, 5)->create(['group_id' => 1, 'quizz_id' => $quizz->id]);

        $questions_formatted = collect();
        foreach ($questions as $quest)
            $questions_formatted->push([
                'id' => strval($quest->id),
                'question' => $quest->question,
                'bg_url' => $quest->bg_url,
                'created_at' => $quest->created_at->toDateTimeString()
            ]);

        $this->withHeaders($this->headers)
            ->query('questions', ['data' => ['id', 'question', 'bg_url', 'created_at']])
            ->assertJson([
                'data' => [
                    'questions' => [
                        'data' => $questions_formatted->toArray()
                    ]
                ]
            ]);
    }

    /**
     * Test user get category
     *
     * @return void
     */
    public function testGetQuestion()
    {
        $author = factory(Author::class)->create(['group_id' => 1]);
        $quizz = factory(Quizz::class)->create(['group_id' => 1, 'author_id' => $author->id]);
        $question = factory(Question::class)->create(['group_id' => 1, 'quizz_id' => $quizz->id]);

        $this->withHeaders($this->headers)
            ->query('question', ['id' => $question->id], ['id', 'question', 'bg_url', 'created_at'])
            ->assertJsonFragment([
                'id' => strval($question->id),
                'question' => $question->question,
                'bg_url' => $question->bg_url,
                'created_at' => $question->created_at->toDateTimeString(),
            ]);
    }
    
}