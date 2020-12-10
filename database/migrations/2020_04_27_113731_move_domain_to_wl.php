<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MoveDomainToWl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (\App\Group::all() as $group) {
            if (\Illuminate\Support\Facades\DB::table('group_email_domains')->where('group_id', $group->id)->count() > 0) {
                if ($group->populations()->count() == 0)
                    $group->populations()->create([
                        'name' => 'Default population',
                        'description' => 'Default population',
                        'master_key' => Str::lower(Str::random(5)) // Improve to avoid duplicates
                    ]);
                $pop = $group->populations()->first();
                foreach (\Illuminate\Support\Facades\DB::table('group_email_domains')->where('group_id', $group->id)->get() as $domain) {
                    if (!$group->allowed_domains()->where('domain', $domain->domain)->exists())
                        $group->allowed_domains()->create([
                            'population_id' => $pop->id,
                            'domain' => $domain->domain
                        ]);
                }
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
        //
    }
}
