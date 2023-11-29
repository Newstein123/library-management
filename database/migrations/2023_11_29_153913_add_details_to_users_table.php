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
        Schema::table('users', function (Blueprint $table) {
            $table->string('registered_no')->unique();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('phone', 50);
            $table->text('address', 50);
            $table->string('identification_no', 50);
            $table->integer('is_active')->default(1)->comment('1=active/0=ban');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
