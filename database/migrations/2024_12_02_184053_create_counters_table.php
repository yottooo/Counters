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
        Schema::create('counters', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Type of the counter (polymorphic relation)
            $table->unsignedBigInteger('parent_id'); // Foreign key for the parent
            $table->string('type'); // Type of the counter (polymorphic relation)
            $table->unsignedBigInteger('type_id'); // ID of the related type
            $table->timestamps();
            $table->foreign('parent_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('counters');
    }
};
