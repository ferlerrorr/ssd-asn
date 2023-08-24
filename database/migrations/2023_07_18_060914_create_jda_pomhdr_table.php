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
        Schema::create('jda_pomhdr', function (Blueprint $table) {
            $table->id('jp_id');
            $table->string('jp_PONUMB', 155)->index()->unique();
            $table->string('jp_POVNUM', 155);
            $table->string('jp_PONOT1', 155)->nullable();
            $table->string('jp_POSTAT', 155);
            $table->string('jp_date', 155)->nullable();
            $table->string('jp_remarks', 155)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jda_pomhdr');
    }
};
