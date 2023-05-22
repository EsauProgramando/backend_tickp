<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rolpermiso', function (Blueprint $table) {
            $table->id();
            $table->string('namepermido', 140)->nullable();
            $table->integer('create')->default(0);
            $table->integer('read')->default(0);
            $table->integer('update')->default(0);
            $table->integer('delete')->default(0);

            $table->unsignedBigInteger('submodules_id')->nullable();
            $table->foreign('submodules_id')->references('id')->on('submodules')
                ->onDelete('set null');
             $table->unsignedBigInteger('rols_id')->nullable();
            $table->foreign('rols_id')->references('id')->on('rols')
                ->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rolpermiso');
    }
};
