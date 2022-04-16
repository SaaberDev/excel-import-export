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
        Schema::create('import_logs', function (Blueprint $table) {
            $table->id();
            $table->string('imported_to');
            $table->integer('imported_by');
            $table->json('error_log');
            $table->integer('total_records');
            $table->integer('total_success');
            $table->integer('total_errors');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('import_logs');
    }
};
