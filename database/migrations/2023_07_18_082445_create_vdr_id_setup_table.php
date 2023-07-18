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
        Schema::create('vdr_id_setup', function (Blueprint $table) {
            $table->id('v_id');
            $table->string('v_vname', 155);
            $table->string('v_vid', 155);
            $table->string('v_po_ref', 155);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vdr_id_setup');
    }
};
