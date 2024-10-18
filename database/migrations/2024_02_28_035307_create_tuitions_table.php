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
        Schema::create('tuitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_tuition_id');
            $table->integer('nominal');
            $table->float('nominal_percentage', 2, 0);
            $table->date('period');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('request_tuition_id')->references('id')->on('request_tuitions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tuitions');
    }
};
