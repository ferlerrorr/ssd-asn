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
        Schema::create('inv_hdr', function (Blueprint $table) {
            $table->id('idi');
            $table->string('InvNo', 50)->unique();
            $table->string('InvDate', 50)->nullable();
            $table->string('InvAmt', 50)->nullable();
            $table->string('DiscAmt', 50)->nullable();
            $table->string('StkFlag', 50)->nullable();
            $table->string('VendorID', 50)->nullable();
            $table->string('VendorName', 50)->nullable();
            $table->string('PORef', 50)->index();
            $table->string('Duplicate_PO', 50)->nullable();
            $table->string('SupCode', 50)->nullable();
            $table->timestamp('tStamp')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inv_hdr');
    }
};
