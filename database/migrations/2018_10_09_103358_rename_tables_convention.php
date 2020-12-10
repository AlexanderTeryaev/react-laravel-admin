<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameTablesConvention extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('featured_quizz', 'featured_quizzes');
        Schema::rename('groupconfigs', 'group_configs');
        Schema::rename('groupemaildomains', 'group_email_domains');
        Schema::rename('groupemailwhitelist', 'group_emails_whitelist');
        Schema::rename('useremailsvalidation', 'user_email_validations');
        Schema::rename('usergroups', 'user_groups');
        Schema::rename('userpoints', 'user_points');
        Schema::rename('usersanswer', 'user_answers');
        Schema::rename('userstatistics', 'user_statistics');
        Schema::rename('usersubscriptions', 'user_subscriptions');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('category_quizzes', 'categories_quizz');
        Schema::rename('featured_quizzes', 'featured_quizz');
        Schema::rename('group_configs', 'groupconfigs');
        Schema::rename('group_email_domains', 'groupemaildomains');
        Schema::rename('group_emails_whitelist', 'groupemailwhitelist');
        Schema::rename('user_email_validations', 'useremailsvalidation');
        Schema::rename('user_groups', 'usergroups');
        Schema::rename('user_points', 'userpoints');
        Schema::rename('user_answers','usersanswer');
        Schema::rename('user_statistics', 'userstatistics');
        Schema::rename('user_subscriptions', 'usersubscriptions');
    }
}
