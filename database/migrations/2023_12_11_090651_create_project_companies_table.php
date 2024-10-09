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
        Schema::create('project_companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects', 'id')->nullOnDelete();
            $table->string('name');
            $table->string('code', 6)->comment('To save company code from eprocurement');
            $table->tinyInteger('eproc_company_id')->comment('To save company id from eprocurement');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_companies');
    }
};
