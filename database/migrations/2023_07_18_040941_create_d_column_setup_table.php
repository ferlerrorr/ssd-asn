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
        Schema::create('d_column_setup', function (Blueprint $table) {
            $table->id('D_id');
            $table->string('D_vendor', 155)->nullable();
            $table->string('D_file_type', 155)->nullable();
            $table->string('D_Prefix', 155)->nullable();
            $table->bigInteger('D_vid')->unique();
            $table->string('D_InvNo', 155)->nullable();
            $table->string('D_ItemCode', 155)->nullable();
            $table->string('D_ItemName', 155)->nullable();
            $table->string('D_ConvFact2', 155)->nullable();
            $table->string('D_UOM', 155)->nullable();
            $table->string('D_UnitCost', 155)->nullable();
            $table->string('D_QtyShip', 155)->nullable();
            $table->string('D_QtyFree', 155)->nullable();
            $table->string('D_GrossAmt', 155)->nullable();
            $table->string('D_PldAmt', 155)->nullable();
            $table->string('D_NetAmt', 155)->nullable();
            $table->string('D_SupCode', 155)->nullable();

            // Add an index on 'D_vendor' column
            $table->index('D_vendor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('d_column_setup');
    }
};
