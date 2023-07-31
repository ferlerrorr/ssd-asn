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
        Schema::create('inv_dtl', function (Blueprint $table) {
            $table->string('InvNo', 50);
            $table->string('ItemCode', 50)->nullable();
            $table->string('ItemName', 50)->nullable();
            $table->string('ConvFact2', 50)->nullable();
            $table->string('UOM', 50)->nullable();
            $table->string('UnitCost', 50)->nullable();
            $table->string('QtyShip', 50)->nullable();
            $table->string('QtyFree', 50)->nullable();
            $table->string('GrossAmt', 50)->nullable();
            $table->string('PldAmt', 50)->nullable();
            $table->string('NetAmt', 50)->nullable();
            $table->string('SupCode', 50)->nullable();
            $table->string('TransactionCode', 50)->unique();
            $table->timestamp('tStamp')->useCurrent();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inv_dtl');
    }
};
