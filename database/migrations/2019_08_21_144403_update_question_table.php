<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('questions', 'good', 'answer_1', 'answer_2')) {
            Schema::table('questions', function (Blueprint $table) {
                $table->string('good_answer')->after('question');
                $table->string('bad_answer')->after('good_answer');
            });

            $questions = DB::table('questions')->get();

            foreach ($questions as $question) {
                $quest = DB::table('questions')->where('id', '=', $question->id);
                if ($quest != null) {
                    if ($question->good == 'ANSWER_1') {
                        $quest->update([
                            'good_answer' => $question->answer_1,
                            'bad_answer' => $question->answer_2
                        ]);
                    } else {
                        $quest->update([
                            'good_answer' => $question->answer_2,
                            'bad_answer' => $question->answer_1
                        ]);
                    }
                }
           }
            Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('answer_1');
            $table->dropColumn('answer_2');
            $table->dropColumn('good');
            });
        }
        if (Schema::hasColumn('question_imports', 'good', 'answer_1', 'answer_2')) {
            Schema::table('question_imports', function (Blueprint $table) {
                $table->string('good_answer')->after('question');
                $table->string('bad_answer')->after('question');
            });

            $question_imports = DB::table('question_imports')->get();

            foreach ($question_imports as $question) {
                $quest = DB::table('question_imports')->where('id', '=', $question->id);
                if ($quest != null) {
                    if ($question->good == 'ANSWER_1') {
                        $quest->update([
                            'good_answer' => $question->answer_1,
                            'bad_answer' => $question->answer_2
                        ]);
                    } else {
                        $quest->update([
                            'good_answer' => $question->answer_2,
                            'bad_answer' => $question->answer_1
                        ]);
                    }
                }
            }
            Schema::table('question_imports', function (Blueprint $table) {
                $table->dropColumn('answer_1');
                $table->dropColumn('answer_2');
                $table->dropColumn('good');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('questions', 'good_answer', 'bad_answer')) {
            Schema::table('questions', function (Blueprint $table) {
                $table->string('answer_1')->after('question');
                $table->string('answer_2')->after('answer_1');
                $table->enum('good', ['ANSWER_1', 'ANSWER_2'])->after('answer_2');
            });
            $questions = DB::table('questions')->get();

            foreach ($questions as $question) {
                $quest = DB::table('questions')->where('id', '=', $question->id);
                if ($quest != null) {
                    $res = rand(0, 1);
                    if ($res) {
                        $quest->update([
                            'good' => 'ANSWER_1',
                            'answer_1' => $question->good_answer,
                            'answer_2' => $question->bad_answer
                        ]);
                    } else {
                        $quest->update([
                            'good' => 'ANSWER_2',
                            'answer_1' => $question->bad_answer,
                            'answer_2' => $question->good_answer
                        ]);
                    }
                }
            }
            Schema::table('questions', function (Blueprint $table) {
                $table->dropColumn('good_answer');
                $table->dropColumn('bad_answer');
            });
        }
        if (Schema::hasColumn('question_imports', 'good_answer', 'bad_answer')) {
            Schema::table('question_imports', function (Blueprint $table) {
                $table->string('answer_1')->after('question');
                $table->string('answer_2')->after('answer_1');
                $table->enum('good', ['ANSWER_1', 'ANSWER_2'])->after('answer_2');
            });
            $question_imports = DB::table('question_imports')->get();

            foreach ($question_imports as $question) {
                $quest = DB::table('question_imports')->where('id', '=', $question->id);
                if ($quest != null) {
                    $res = rand(0, 1);
                    if ($res) {
                        $quest->update([
                            'good' => 'ANSWER_1',
                            'answer_1' => $question->good_answer,
                            'answer_2' => $question->bad_answer
                        ]);
                    } else {
                        $quest->update([
                            'good' => 'ANSWER_2',
                            'answer_1' => $question->bad_answer,
                            'answer_2' => $question->good_answer
                        ]);
                    }
                }
            }
            Schema::table('question_imports', function (Blueprint $table) {
                $table->dropColumn('good_answer');
                $table->dropColumn('bad_answer');
            });
        }
    }
}
