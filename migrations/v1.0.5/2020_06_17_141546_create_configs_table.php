<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigsTable extends Migration
{
    public function up(): void
    {
        Schema::create(
            'configs',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('code', 100)->unique();
                $table->text('value')->nullable();
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('configs');
    }
}
