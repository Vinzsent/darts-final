<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('check_in', function (Blueprint $table) {
            if (! Schema::hasColumn('check_in', 'inventory_id')) {
                $table->unsignedInteger('inventory_id')->nullable()->after('items_to_check_in')->index();
            }
            if (! Schema::hasColumn('check_in', 'personnel_id')) {
                $table->unsignedInteger('personnel_id')->nullable()->after('inventory_id')->index();
            }
            if (! Schema::hasColumn('check_in', 'quantity')) {
                $table->unsignedInteger('quantity')->default(1)->after('personnel_id');
            }
            if (! Schema::hasColumn('check_in', 'comments')) {
                $table->text('comments')->nullable()->after('location');
            }
            if (! Schema::hasColumn('check_in', 'print_receipt')) {
                $table->boolean('print_receipt')->default(false)->after('comments');
            }
            if (! Schema::hasColumn('check_in', 'checked_in_by')) {
                $table->string('checked_in_by', 100)->nullable()->after('print_receipt');
            }
        });
    }

    public function down(): void
    {
        Schema::table('check_in', function (Blueprint $table) {
            foreach (['checked_in_by', 'print_receipt', 'comments', 'quantity', 'personnel_id', 'inventory_id'] as $col) {
                if (Schema::hasColumn('check_in', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
