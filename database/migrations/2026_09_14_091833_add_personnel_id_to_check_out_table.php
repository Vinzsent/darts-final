<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('check_out', function (Blueprint $table) {
            if (! Schema::hasColumn('check_out', 'personnel_id')) {
                $table->unsignedInteger('personnel_id')->nullable()->after('inventory_id')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('check_out', function (Blueprint $table) {
            if (Schema::hasColumn('check_out', 'personnel_id')) {
                $table->dropIndex(['personnel_id']);
                $table->dropColumn('personnel_id');
            }
        });
    }
};
