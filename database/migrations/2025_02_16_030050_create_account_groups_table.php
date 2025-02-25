<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('account_groups', function (Blueprint $table) {

            $table->id();
            $table->string('name');
            $table->tinyInteger('active')->default(1);
            $table->enum('type', ['asset', 'liability', 'equity', 'income', 'expense']);
            $table->integer('level')->default(1);
            $table->unsignedBigInteger('under')->nullable();
            $table->timestamps();

            $table->unique(['name', 'level'], 'name_level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('account_groups');
    }
};
