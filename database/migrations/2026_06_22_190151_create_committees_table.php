<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('committees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('abbreviation');
            $table->string('email');
            $table->string('description')->default('');
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('committee_member', function (Blueprint $table) {
            $table->id();
            $table->foreignId('committee_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('member_id'); //the user table uses a different id column type than the foreignId() method
            $table->foreign('member_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('is_chair')->default(false);
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('committee_member');
        Schema::dropIfExists('committees');
    }
};
