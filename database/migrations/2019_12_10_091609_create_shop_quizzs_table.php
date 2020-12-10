<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopQuizzsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_quizzes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shop_training_id');
            $table->unsignedBigInteger('shop_author_id');
            $table->text('name');
            $table->string('image_url');
            $table->longText('description');
            $table->enum('difficulty', ['EASY', 'MEDIUM', 'HARD']);
            $table->json('tags');
            $table->timestamps();

            $table->foreign('shop_author_id')
                ->references('id')->on('shop_authors')
                ->onDelete('cascade');
            $table->foreign('shop_training_id')
                ->references('id')->on('shop_trainings')
                ->onDelete('cascade');
        });
    }

    public static function handle(Error $error, Closure $next): array
    {
        $underlyingException = $error->getPrevious();
        if (!$underlyingException instanceof RendersErrorsExtensions) {
            // Only report the exception if it is renderless.
            // Most exceptions that render, are user-errors, that should not be logged (eg. invalid input, etc.).
            Bugsnag::notifyException($underlyingException);
        }
        return $next($error);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_quizzes');
    }
}
