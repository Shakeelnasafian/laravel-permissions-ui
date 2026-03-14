<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('permissions')) {
            return;
        }

        if (Schema::hasColumn('permissions', 'group')) {
            return;
        }

        Schema::table('permissions', function (Blueprint $table) {
            $table->string('group')->nullable()->after('guard_name')->index();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('permissions')) {
            return;
        }

        if (! Schema::hasColumn('permissions', 'group')) {
            return;
        }

        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('group');
        });
    }
};
