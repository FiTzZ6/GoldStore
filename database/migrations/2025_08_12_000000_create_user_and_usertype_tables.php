<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('usertype', function (Blueprint $table) {
            $table->id('usertypeid');
            $table->string('usertype', 50);
        });

        Schema::create('user', function (Blueprint $table) {
            $table->id('kduser');
            $table->string('name', 100);
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->unsignedBigInteger('usertype');
            $table->string('kdtoko', 50);
            $table->timestamp('createddate')->nullable();

            $table->foreign('usertype')->references('usertypeid')->on('usertype');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user');
        Schema::dropIfExists('usertype');
    }
};
