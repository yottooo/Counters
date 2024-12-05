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
        Schema::create('pregnancies', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->date('termin_date'); // Termination or due date
            $table->unsignedBigInteger('parent_id'); // Foreign key for the parent
            $table->timestamps(); // Created_at and updated_at timestamps

            $table->foreign('parent_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pregnancies');
    }
};
