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
            $table->string('D_vendor', 155);
            $table->string('D_file_type', 155);
            $table->string('D_Prefix', 155);
            $table->string('D_InvNo', 155);
            $table->string('D_Itemcode', 155);
            $table->string('D_ItemName', 155);
            $table->string('D_ConvFact2', 155);
            $table->string('D_UOM', 155);
            $table->string('D_UnitCost', 155);
            $table->string('D_QtyShip', 155);
            $table->string('D_QtyFree', 155);
            $table->string('D_GrossAmt', 155);
            $table->string('D_PldAmt', 155);
            $table->string('D_NetAmt', 155);
            $table->string('D_SupCode', 155);

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
