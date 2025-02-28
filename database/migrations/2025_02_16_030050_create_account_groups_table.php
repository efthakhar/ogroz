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
            $table->string('name')->unique();
            $table->enum('type', ['asset', 'liability', 'equity', 'income', 'expense']);
            $table->unsignedBigInteger('parent_account_group_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('account_groups');
    }
};
