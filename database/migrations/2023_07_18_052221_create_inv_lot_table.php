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
        Schema::create('inv_lot', function (Blueprint $table) {
            $table->id();
            $table->string('InvNo', 50);
            $table->string('ItemCode', 50)->nullable();
            $table->string('LotNo', 50)->nullable();
            $table->string('ExpiryMM', 50)->nullable();
            $table->string('ExpiryDD', 50)->nullable();
            $table->string('ExpiryYYYY', 50)->nullable();
            $table->string('Qty', 50)->nullable();
            $table->timestamp('tStamp')->useCurrent();
            $table->string('SupCode', 50)->nullable();
            $table->string('TransactionCode', 50)->unique();
            $table->string('remarks', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inv_lot');
    }
};
