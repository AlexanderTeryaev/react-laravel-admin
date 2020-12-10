<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLeavedAtFieldInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_invitations', function (Blueprint $table) {
            $table->timestamp('leaved_at')->nullable()->after('accepted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_invitations', function (Blueprint $table) {
            $table->dropColumn('leaved_at');
        });
    }
}
