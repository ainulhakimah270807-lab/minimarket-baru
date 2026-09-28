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
        // 1. Tabel orders (Acara 17 - Join & Subquery)
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total_price', 12, 2);
            $table->timestamps();
        });

        // 2. Tabel employees (Acara 17 - Agregat Max & Min)
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('salary', 12, 2);
            $table->timestamps();
        });

        // 3. Tabel profiles (Acara 19 - One to One)
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->text('bio')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        });

        // 4. Tabel roles & role_user (Acara 19 - Many to Many)
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('profiles');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('orders');
    }
};
