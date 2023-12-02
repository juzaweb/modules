<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'network_user_metas',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id');
                $table->string('meta_key', 150)->index();
                $table->text('meta_value')->nullable();
                $table->unique(['user_id', 'meta_key']);

                $table->foreign('user_id')
                    ->references('id')
                    ->on('network_user_metas')
                    ->onDelete('cascade');
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('network_user_metas');
    }
};
