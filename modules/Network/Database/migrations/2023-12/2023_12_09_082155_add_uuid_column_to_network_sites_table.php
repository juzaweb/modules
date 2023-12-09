<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Juzaweb\Network\Models\Site;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('network_sites', function (Blueprint $table) {
            $table->uuid()->nullable()->unique();
        });

        Site::get()->each(function ($site) {
            $site->uuid = Str::uuid();
            $site->save();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('network_sites', function (Blueprint $table) {
            $table->dropColumn(['uuid']);
        });
    }
};
