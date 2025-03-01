<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('active')->default(1);
            $table->string('accountable_type')->nullable();
            $table->unsignedBigInteger('accountable_id')->nullable();
            $table->string('name')->unique();
            $table->string('number')->unique()->nullable();
            $table->unsignedBigInteger('account_group_id');
            $table->double('opening_debit')->default(0);
            $table->double('opening_credit')->default(0);
            $table->unsignedBigInteger('opening_base_currency_id')->nullable();
            $table->unsignedBigInteger('opening_currency_id')->nullable();
            $table->unsignedBigInteger('opening_exchange_rate')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
