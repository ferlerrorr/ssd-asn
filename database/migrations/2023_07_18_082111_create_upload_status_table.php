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
        Schema::create('upload_status', function (Blueprint $table) {
            $table->id('up_id');
            $table->string('up_vendor_name', 155);
            $table->string('up_vendor_number', 155);
            $table->string('hdr_percent', 155);
            $table->string('dtl_percent', 155);
            $table->string('dtl_count', 155);
            $table->string('error_logs_count', 155);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('upload_status');
    }
};
