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
        Schema::create('l_column_setup', function (Blueprint $table) {
            $table->id('L_id');
            $table->string('L_vendor', 155);
            $table->string('L_file_type', 155);
            $table->string('L_InvNo', 155);
            $table->string('L_ItemCode', 155);
            $table->string('L_LotNo', 155);
            $table->string('L_ExpiryMM', 155)->nullable();
            $table->string('L_ExpiryDD', 155)->nullable();
            $table->string('L_ExpiryYYYY', 155)->nullable();
            $table->string('L_Expiry', 155)->nullable();
            $table->string('L_Qty', 155);
            $table->string('L_SupCode', 155);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('l_column_setup');
    }
};
