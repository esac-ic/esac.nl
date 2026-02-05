<?php

use App\Models\Committee;
use App\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('committees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('abbreviation')->nullable();
            $table->string('description', 1000)->nullable(); //length is eyeballed a bit, currently (2026) the longest description is ~600 characters
            $table->string('email')->nullable();
            $table->foreignIdFor(User::class, 'chair_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('committee_user', function (Blueprint $table) {
            $table->foreignIdFor(Committee::class, 'committee_id')->nullable();
            $table->foreignIdFor(User::class, 'user_id')->nullable();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('committee_user');
        Schema::dropIfExists('committees');
    }
};
