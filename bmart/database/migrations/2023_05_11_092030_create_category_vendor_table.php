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
        Schema::create('category_vendor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained();
            $table->foreignId('user_id')->constrained('users')->where('isVendor', 1)->onDelete('cascade')->nullable();
            $table->boolean('deleted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_vendors');
    }
};
