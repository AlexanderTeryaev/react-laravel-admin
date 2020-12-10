<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReworkQuizzTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
            $table->dropColumn('qz_latitude');
            $table->dropColumn('qz_longitude');
            $table->dropColumn('radius');
            $table->dropColumn('is_geolocalized');
        });
        Schema::table('quizzes', function (Blueprint $table) {
            $table->boolean('is_geolocalized')->default(false)->after('enduro_limit');
        });

        DB::statement('ALTER TABLE quizzes DROP FOREIGN KEY quizzes_author_id_foreign, drop KEY quizzes_author_id_foreign');
        DB::statement('ALTER TABLE quizzes CHANGE author_id author_id_old INT(10)');

        Schema::table('quizzes', function (Blueprint $table) {
            $table->unsignedInteger('author_id')->after('group_id');
        });
        DB::table('quizzes')->update([
            'author_id' => DB::raw('author_id_old'),
        ]);
        Schema::table('quizzes', function (Blueprint $table) {

            $table->foreign('author_id')
                ->references('id')
                ->on('authors')
                ->onDelete('cascade');

        });

        DB::statement('ALTER TABLE quizzes ADD COLUMN latitude DOUBLE(16,5) DEFAULT NULL AFTER `is_geolocalized`');
        DB::statement('ALTER TABLE quizzes ADD COLUMN longitude DOUBLE(16,5) DEFAULT NULL AFTER `latitude`');

        Schema::table('quizzes', function (Blueprint $table) {
            $table->integer('radius')->nullable()->after('longitude');
            $table->string('image_url')->nullable()->after('tags');
            $table->string('default_questions_image')->nullabe()->after('image_url');
        });

        DB::table('quizzes')->update([
            'image_url' => DB::raw('bg_url'),
            'default_questions_image' => DB::raw('default_img_url'),
        ]);
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('bg_url');
            $table->dropColumn('default_img_url');
            $table->dropColumn('author_id_old');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->softDeletes();
            DB::statement('ALTER TABLE quizzes CHANGE image_url bg_url INT(10)');
            DB::statement('ALTER TABLE quizzes CHANGE default_questions_image default_img_url INT(10)');
        });
    }
}
