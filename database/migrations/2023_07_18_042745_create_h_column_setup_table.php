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
        Schema::create('h_column_setup', function (Blueprint $table) {
            $table->id('H_id');
            $table->string('H_vendor', 155)->nullable();
            $table->bigInteger('H_vid')->unique();
            $table->string('H_file_type', 155)->nullable();
            $table->string('H_InvNo', 155)->nullable();
            $table->string('H_InvDate', 155)->nullable();
            $table->string('H_InvAmt', 155)->nullable();
            $table->string('H_DiscAmt', 155)->nullable();
            $table->string('H_StkFlag', 155)->nullable();
            $table->string('H_VendorID', 155)->nullable();
            $table->string('H_VendorName', 155)->nullable();
            $table->string('H_PORef', 155)->nullable();
            $table->string('H_SupCode', 155)->nullable();
            $table->index('H_vid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('h_column_setup');
    }
};
