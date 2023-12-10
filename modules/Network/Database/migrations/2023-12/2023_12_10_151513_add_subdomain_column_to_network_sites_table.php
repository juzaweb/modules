<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Juzaweb\Network\Models\Site;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('network_sites', function (Blueprint $table) {
            $table->string('subdomain', 150)->unique()->nullable();
            $table->string('domain', 150)->nullable()->index()->change();
        });

        Site::get()->each(function ($site) {
            $site->subdomain = $site->domain;
            $site->domain = null;
            $site->save();
        });

        Schema::table('network_sites', function (Blueprint $table) {
            $table->dropUnique(['subdomain']);
            $table->string('subdomain')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('network_sites', function (Blueprint $table) {
            $table->dropColumn(['subdomain']);
        });
    }
};
