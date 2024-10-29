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
        Schema::create('request_tuition', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('type_tuition_id');
            $table->string('file');
            $table->integer('nominal');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('remark');
            $table->enum('status', ['Waiting Approval', 'Rejected', 'Fully Approved']);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('member_id')->references('id')->on('group_members');
            $table->foreign('type_tuition_id')->references('id')->on('tuition_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_tuition');
    }
};
