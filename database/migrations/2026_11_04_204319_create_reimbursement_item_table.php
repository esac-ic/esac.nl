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
        Schema::create('reimbursement_items', function (Blueprint $table) {
            $table->id();
            $table->string('item');
            $table->float('amount');
            $table->boolean('esacpas');
            $table->string('image_url');
            $table->unsignedInteger('reimbursement_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reimbursement_item');
    }
};
