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
        Schema::create('kids', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Name of the kid
            $table->unsignedBigInteger('parent_id'); // Foreign key for the parent
            $table->enum('gender', ['male', 'female']); // Gender with predefined values
            $table->date('birthday'); // Birthday field
            $table->timestamps(); // Created_at and updated_at
            $table->foreign('parent_id')->references('id')->on('users')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kids');
    }
};
