<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marriages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('spouse_a_id');
            $table->unsignedBigInteger('spouse_b_id');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['active','divorced','widowed'])->default('active');
            $table->timestamps();
            $table->foreign('spouse_a_id')->references('id')->on('persons')->cascadeOnDelete();
            $table->foreign('spouse_b_id')->references('id')->on('persons')->cascadeOnDelete();
            $table->index(['spouse_a_id','spouse_b_id','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marriages');
    }
};
