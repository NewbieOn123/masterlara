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
        Schema::create('role_access', function (Blueprint $table) {
            $table->id('id_roleaccess');
            $table->integer('idmenu');
            $table->integer('idgroupaccess');
            $table->string('aktif')->length('2');
            $table->dateTime('created_at');
            $table->string('created_by')->length('50')->nullable();
            $table->dateTime('updated_at');
            $table->string('updated_by')->length('50')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_access');
    }
};
