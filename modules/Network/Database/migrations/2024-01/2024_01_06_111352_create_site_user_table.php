<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('network_site_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')
                ->index()
                ->constrained('network_sites')
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->index()
                ->constrained('users')
                ->onDelete('cascade');
        });

        DB::table('network_sites')->chunkById(
            100,
            function ($sites) {
                foreach ($sites as $site) {
                    DB::table('network_site_user')->insert([
                        'site_id' => $site->id,
                        'user_id' => $site->created_by,
                    ]);
                }
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('network_site_user');
    }
};
