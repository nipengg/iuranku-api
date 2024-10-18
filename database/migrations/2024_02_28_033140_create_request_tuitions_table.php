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
        Schema::create('request_tuitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('type_tuition_id');
            $table->integer('nominal');
            $table->string('file');
            $table->enum('status', ['Waiting Approval', 'Fully Approved']);
            $table->date('start_date');
            $table->date('end_date');
            $table->date('request_date');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('member_id')->references('id')->on('group_members');
            $table->foreign('type_tuition_id')->references('id')->on('tuition_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_tuitions');
    }
};
