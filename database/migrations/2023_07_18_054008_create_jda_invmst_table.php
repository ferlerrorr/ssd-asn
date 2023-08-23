<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jda_invmst', function (Blueprint $table) {
            $table->id('ji_id');
            $table->string('ji_INUMBR', 155)->unique();
            $table->string('ji_IMFGNO', 155)->nullable();
            $table->string('ji_IVVNDN', 155)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jda_invmst');
    }
};
