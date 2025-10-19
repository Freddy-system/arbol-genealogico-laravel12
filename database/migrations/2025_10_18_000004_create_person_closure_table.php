<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('person_closure', function (Blueprint $table) {
            $table->unsignedBigInteger('ancestor_id');
            $table->unsignedBigInteger('descendant_id');
            $table->unsignedSmallInteger('depth');
            $table->primary(['ancestor_id','descendant_id']);
            $table->foreign('ancestor_id')->references('id')->on('persons')->cascadeOnDelete();
            $table->foreign('descendant_id')->references('id')->on('persons')->cascadeOnDelete();
            $table->index(['ancestor_id','depth']);
            $table->index(['descendant_id','depth']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('person_closure');
    }
};
