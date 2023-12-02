<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'subsite_users',
            function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email', 150)->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password')->nullable();
                $table->rememberToken();
                $table->timestamps();
                $table->string('avatar', 150)->nullable();
                $table->boolean('is_admin')->default(0);
                $table->string('status', 50)
                    ->default('active')
                    ->comment('unconfimred, banned, active');
                $table->string('language', 5)->default('en');
                $table->string('verification_token')->nullable();
                $table->unsignedBigInteger('site_id')->index();
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
        Schema::dropIfExists('subsite_users');
    }
};
