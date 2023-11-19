<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'theme_configs',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('code', 50)->index();
                $table->string('theme', 150)->index();
                $table->text('value')->nullable();
                $table->unique(['code', 'theme']);
                $table->timestamps();
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('theme_configs');
    }
};
