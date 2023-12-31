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
        Schema::table(
            'media_files',
            function (Blueprint $table) {
                $table->string('image_size', 50)->nullable();
                $table->foreignId('parent_id')->index()->nullable()
                    ->constrained('media_files')
                    ->onDelete('cascade');
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
        Schema::table(
            'media_files',
            function (Blueprint $table) {
                $table->dropForeign(['parent_id']);
                $table->dropColumn(['parent_id', 'image_size']);
            }
        );
    }
};
