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
            // Add ElimuCore-specific columns
            $table->string('phone')->unique()->nullable()->after('email');
            $table->enum('role', ['super_admin', 'principal', 'deputy_academic', 'deputy_admin', 'teacher', 'bursar', 'student', 'parent'])->after('password');
            $table->boolean('is_approved')->default(false)->after('role');
            $table->timestamp('approved_at')->nullable()->after('is_approved');
            $table->foreignId('approved_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->text('approval_reason')->nullable();
            $table->string('employee_id')->unique()->nullable();
            $table->string('staff_number')->unique()->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->boolean('is_active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'role',
                'is_approved',
                'approved_at',
                'approved_by',
                'approval_reason',
                'employee_id',
                'staff_number',
                'last_login_at',
                'is_active'
            ]);
        });
    }
};
