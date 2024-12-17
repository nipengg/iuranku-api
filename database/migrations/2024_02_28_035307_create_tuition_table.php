<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tuition', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_tuition_id');
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('type_tuition_id');
            $table->integer('nominal');
            $table->date('period');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('request_tuition_id')->references('id')->on('request_tuition');
            $table->foreign('type_tuition_id')->references('id')->on('tuition_type');
            $table->foreign('member_id')->references('id')->on('group_members');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tuition');
    }
};
