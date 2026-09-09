<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_request', function (Blueprint $table) {
            $table->string('department_unit')->nullable()->after('user_id');
            $table->unsignedBigInteger('noted_by')->nullable();
            $table->timestamp('noted_date')->nullable();
            $table->unsignedBigInteger('checked_by')->nullable();
            $table->timestamp('checked_date')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_date')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_date')->nullable();
            $table->unsignedBigInteger('issued_by')->nullable();
            $table->timestamp('issued_date')->nullable();
            $table->text('remarks')->nullable();
            $table->index('status');
            $table->index('date_requested');
        });
    }

    public function down(): void
    {
        Schema::table('property_request', function (Blueprint $table) {
            $table->dropColumn([
                'department_unit', 'noted_by', 'noted_date', 'checked_by', 'checked_date',
                'verified_by', 'verified_date', 'approved_by', 'approved_date',
                'issued_by', 'issued_date', 'remarks',
            ]);
        });
    }
};
