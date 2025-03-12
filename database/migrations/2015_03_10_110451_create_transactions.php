<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('serial');
            $table->foreignId('user_id')->constrained('users')->nullable();
            $table->foreignId('sender_wallet')->constrained('wallets')->onDelete('cascade')->nullable();
            $table->foreignId('receiver_wallet')->constrained('wallets')->onDelete('cascade')->nullable();
            $table->dateTime('date');
            $table->decimal('amount');
            $table->foreignId('state_id')->constrained('states')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
