<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'sub_admin', 'donor') NOT NULL DEFAULT 'donor'");

        Schema::table('users', function (Blueprint $table) {
            $table->json('permissions')->nullable()->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('permissions');
        });

        DB::statement("UPDATE users SET role = 'donor' WHERE role = 'sub_admin'");
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'donor') NOT NULL DEFAULT 'donor'");
    }
};
