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
        Schema::create('register_inventaries', function (Blueprint $table) {
            $table->id();
            $table->string('ruc_entidad', 11)->nullable();
            $table->string('codigo_patrimonial', 12)->nullable();
            $table->string('denominacion_bien', 120)->nullable();
            $table->string('actos_de_adquisicion_que_genera_alta', 1)->nullable();
            $table->string('nro_doc_adquisicion', 50)->nullable();
            $table->date('fecha_adquisicion')->nullable();
            $table->decimal('valor_adquisicion', 20, 2)->nullable();
            $table->string('tipo_uso_cuenta', 1)->nullable();
            $table->string('tipo_cuenta', 1)->nullable();
            $table->string('nro_cuenta_contable', 20)->nullable();
            $table->string('cta_con_seguro', 2)->nullable();
            $table->string('estado_bien', 1)->nullable();
            $table->string('condicion', 1)->nullable();
            
            $table->unsignedBigInteger('user_id')->nullable();

            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('set null');
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('register_inventaries');
    }
};
