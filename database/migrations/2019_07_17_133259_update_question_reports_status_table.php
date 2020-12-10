<?php

use App\QuestionReport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateQuestionReportsStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $reports = DB::table('question_reports')->get();
        foreach ($reports as $report)
        {
            $report_table = QuestionReport::findOrFail($report->id);
            if ($report->resolved == 1)
            {
                $report_table->update(['status' => 'resolved']);
            }
            else
            {
                $report_table->update(['status' => 'pending']);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $reports = DB::table('question_reports')->get();
        foreach ($reports as $report) {
            $report_table = QuestionReport::findOrFail($report->id);
            $report_table->update(['status' => null]);
        }
    }
}
