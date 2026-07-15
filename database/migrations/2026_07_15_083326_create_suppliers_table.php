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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            
            $table->string('supplier_code')->unique();

            $table->string('company_name');

            $table->string('contact_person');

            $table->string('email')->nullable();

            $table->string('phone');

            $table->string('alternative_phone')->nullable();

            $table->text('address')->nullable();

            $table->string('city')->nullable();

            $table->string('country')->default('Kenya');

            $table->string('tax_number')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
