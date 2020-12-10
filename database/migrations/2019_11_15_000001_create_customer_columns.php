<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->string('stripe_id')->after('free_tier')->nullable()->index();
            $table->string('card_holder_name')->after('stripe_id')->nullable();
            $table->string('billing_address')->after('card_holder_name')->nullable();
            $table->string('card_brand')->after('billing_address')->nullable();
            $table->string('card_last_four', 4)->after('card_brand')->nullable();
            $table->timestamp('trial_ends_at')->after('card_last_four')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_id',
                'card_holder_name',
                'billing_address',
                'card_brand',
                'card_last_four',
                'trial_ends_at',
            ]);
        });
    }
}
