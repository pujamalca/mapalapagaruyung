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
            // Academic Information
            $table->string('nim')->nullable()->after('username');
            $table->string('major')->nullable()->after('nim'); // Jurusan
            $table->string('faculty')->nullable()->after('major'); // Fakultas
            $table->integer('enrollment_year')->nullable()->after('faculty'); // Tahun masuk kampus

            // Mapala Membership Information
            $table->foreignId('cohort_id')->nullable()->after('enrollment_year')->constrained()->nullOnDelete();
            $table->string('member_number')->unique()->nullable()->after('cohort_id');
            $table->integer('mapala_join_year')->nullable()->after('member_number');
            $table->enum('member_status', [
                'prospective', // Calon Anggota
                'junior',      // Anggota Muda
                'member',      // Anggota
                'alumni'       // Alumni
            ])->default('prospective')->after('mapala_join_year');

            // Personal Information
            $table->text('address')->nullable()->after('bio');
            $table->string('blood_type')->nullable()->after('address');
            $table->text('medical_history')->nullable()->after('blood_type');

            // Emergency Contact (JSON)
            $table->json('emergency_contact')->nullable()->after('medical_history');
            // Structure: { name: string, relationship: string, phone: string }

            // Skills & Certifications (JSON array)
            $table->json('skills')->nullable()->after('emergency_contact');
            // Structure: [{ skill: string, level: string, certified: boolean, certificate_date: date }]

            // Indexes
            $table->index('nim');
            $table->index('member_number');
            $table->index('member_status');
            $table->index('cohort_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['cohort_id']);

            $table->dropIndex(['nim']);
            $table->dropIndex(['member_number']);
            $table->dropIndex(['member_status']);
            $table->dropIndex(['cohort_id']);

            $table->dropColumn([
                'nim',
                'major',
                'faculty',
                'enrollment_year',
                'cohort_id',
                'member_number',
                'mapala_join_year',
                'member_status',
                'address',
                'blood_type',
                'medical_history',
                'emergency_contact',
                'skills',
            ]);
        });
    }
};
