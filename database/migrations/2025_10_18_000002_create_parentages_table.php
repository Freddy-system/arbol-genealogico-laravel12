<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parentages', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id');
            $table->unsignedBigInteger('child_id');
            $table->enum('type', ['mother','father']);
            $table->primary(['parent_id','child_id','type']);
            $table->foreign('parent_id')->references('id')->on('persons')->cascadeOnDelete();
            $table->foreign('child_id')->references('id')->on('persons')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parentages');
    }
};
