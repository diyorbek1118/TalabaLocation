<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('faculty')->nullable();
            $table->string('group_name')->nullable();
            $table->string('course')->nullable();
            $table->string('tutor')->nullable();
            $table->string('rent_area')->nullable();
            $table->text('rent_address')->nullable();
            $table->text('rent_map_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};

