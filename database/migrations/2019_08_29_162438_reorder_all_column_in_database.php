<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use function foo\func;

class ReorderAllColumnInDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_answers', function (Blueprint $table) {
            $table->renameColumn('is_enduro', 'old_is_enduro');
        });

        Schema::table('user_answers', function (Blueprint $table) {
            $table->boolean('is_enduro')->after('answered_at');
        });

        DB::table('user_answers')->update([
            'is_enduro' => DB::raw('old_is_enduro')
        ]);

        Schema::table('user_answers', function (Blueprint $table) {
            $table->dropColumn('old_is_enduro');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->renameColumn('status', 'old_status');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->boolean('status')->after('remember_token');
        });

        DB::table('admins')->update([
            'status' => DB::raw('old_status')
        ]);

        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('old_status');
        });

        Schema::table('authors', function (Blueprint $table) {
            $table->renameColumn('email', 'old_email');
        });

        Schema::table('authors', function (Blueprint $table) {
            $table->string('email')->nullable()->after('name');
        });

        DB::table('authors')->update([
            'email' => DB::raw('old_email')
        ]);

        Schema::table('authors', function (Blueprint $table) {
            $table->dropColumn('old_email');
        });

        Schema::table('authors', function (Blueprint $table) {
            $table->renameColumn('password', 'old_password');
        });

        Schema::table('authors', function (Blueprint $table) {
            $table->string('password')->after('email');
        });

        DB::table('authors')->update([
            'password' => DB::raw('old_password')
        ]);

        Schema::table('authors', function (Blueprint $table) {
            $table->dropColumn('old_password');
        });

        Schema::table('authors', function (Blueprint $table) {
            $table->renameColumn('onboarded', 'old_onboarded');
        });

        Schema::table('authors', function (Blueprint $table) {
            $table->boolean('onboarded')->default(false)->after('website_link');
        });

        DB::table('authors')->update([
            'onboarded' => DB::raw('old_onboarded')
        ]);

        Schema::table('authors', function (Blueprint $table) {
            $table->dropColumn('old_onboarded');
        });

        Schema::table('blog', function (Blueprint $table) {
            $table->renameColumn('image_url', 'old_image_url');
        });

        Schema::table('blog', function (Blueprint $table) {
            $table->string('image_url')->after('tags');
        });

        DB::table('blog')->update([
            'image_url' => DB::raw('old_image_url')
        ]);

        Schema::table('blog', function (Blueprint $table) {
            $table->dropColumn('old_image_url');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->renameColumn('logo_url', 'old_logo_url');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('logo_url')->after('name');
        });

        DB::table('categories')->update([
            'logo_url' => DB::raw('old_logo_url')
        ]);

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('old_logo_url');
        });

        Schema::table('featured', function (Blueprint $table) {
            $table->renameColumn('status', 'old_status');
        });

        Schema::table('featured', function (Blueprint $table) {
            $table->boolean('status')->after('description');
        });

        DB::table('featured')->update([
            'status' => DB::raw('old_status')
        ]);

        Schema::table('featured', function (Blueprint $table) {
            $table->dropColumn('old_status');
        });

        Schema::table('featured', function (Blueprint $table) {
            $table->renameColumn('order_id', 'old_order_id');
        });

        Schema::table('featured', function (Blueprint $table) {
            $table->integer('order_id')->after('description');
        });

        DB::table('featured')->update([
            'order_id' => DB::raw('old_order_id')
        ]);

        Schema::table('featured', function (Blueprint $table) {
            $table->dropColumn('old_order_id');
        });

       /* Schema::table('question_reports', function (Blueprint $table) {
            $table->renameColumn('status', 'old_status');
        }); */

        DB::statement('ALTER TABLE question_reports CHANGE status old_status ENUM(\'pending\', \'resolved\', \'closed\')');

        Schema::table('question_reports', function (Blueprint $table) {
            $table->enum('status', ['pending', 'resolved', 'closed'])->nullable()->after('report');
        });

        DB::table('question_reports')->update([
            'status' => DB::raw('old_status')
        ]);

        Schema::table('question_reports', function (Blueprint $table) {
            $table->dropColumn('old_status');
        });

        /*Schema::table('question_reports', function (Blueprint $table) {
            $table->renameColumn('review', 'old_review');
        });*/

        DB::statement('ALTER TABLE question_reports CHANGE review old_review VARCHAR(191) ');

        Schema::table('question_reports', function (Blueprint $table) {
            $table->string('review')->nullable()->after('report');
        });

        DB::table('question_reports')->update([
            'review' => DB::raw('old_review')
        ]);

        Schema::table('question_reports', function (Blueprint $table) {
            $table->dropColumn('old_review');
        });

        if (Schema::hasColumn('quizzes', 'enduroLimit')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->renameColumn('enduroLimit', 'old_enduro_limit');
            });

            Schema::table('quizzes', function (Blueprint $table) {
                $table->integer('enduro_limit')->unsigned()->nullable()->after('description');
            });

            DB::table('quizzes')->update([
                'enduro_limit' => DB::raw('old_enduro_limit')
            ]);

            Schema::table('quizzes', function (Blueprint $table) {
                $table->dropColumn('old_enduro_limit');
            });
        }

        Schema::table('quizzes', function (Blueprint $table) {
            $table->renameColumn('default_img_url', 'old_default_img_url');
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->string('default_img_url')->nullable()->after('bg_url');
        });

        DB::table('quizzes')->update([
            'default_img_url' => DB::raw('old_default_img_url')
        ]);

        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('old_default_img_url');
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->renameColumn('qz_latitude', 'old_qz_latitude');
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->float('qz_latitude')->nullable()->after('name');
        });

        DB::table('quizzes')->update([
            'qz_latitude' => DB::raw('old_qz_latitude')
        ]);

        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('old_qz_latitude');
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->renameColumn('qz_longitude', 'old_qz_longitude');
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->float('qz_longitude')->nullable()->after('qz_latitude');
        });

        DB::table('quizzes')->update([
            'qz_longitude' => DB::raw('old_qz_longitude')
        ]);

        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('old_qz_longitude');
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->renameColumn('radius', 'old_radius');
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->integer('radius')->nullable()->after('qz_longitude');
        });

        DB::table('quizzes')->update([
            'radius' => DB::raw('old_radius')
        ]);

        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('old_radius');
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->renameColumn('is_geolocalized', 'old_is_geolocalized');
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->boolean('is_geolocalized')->default(false)->after('radius');
        });

        DB::table('quizzes')->update([
            'is_geolocalized' => DB::raw('old_is_geolocalized')
        ]);

        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('old_is_geolocalized');
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->renameColumn('tags', 'old_tags');
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->json('tags')->after('name');
        });

        DB::table('quizzes')->update([
            'tags' => DB::raw('old_tags')
        ]);

        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('old_tags');
        });

        Schema::table('user_statistics', function (Blueprint $table) {
            $table->renameColumn('score', 'old_score');
        });

        Schema::table('user_statistics', function (Blueprint $table) {
            $table->integer('score')->after('good_answers');
        });

        DB::table('user_statistics')->update([
            'score' => DB::raw('old_score')
        ]);

        Schema::table('user_statistics', function (Blueprint $table) {
            $table->dropColumn('old_score');
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
            $table->renameColumn('enduro_limit', 'enduroLimit');
        });
    }
}
