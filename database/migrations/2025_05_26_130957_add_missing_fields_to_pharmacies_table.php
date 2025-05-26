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
        Schema::table('pharmacies', function (Blueprint $table) {
            $table->string('pharmacy_name')->after('user_id');
            $table->string('pharmacist_in_charge')->nullable()->after('pharmacy_name');
            $table->text('description')->nullable()->after('pharmacist_in_charge');
            $table->string('address')->nullable()->after('description');
            $table->string('city')->nullable()->after('address');
            $table->string('state')->nullable()->after('city');
            $table->string('postal_code')->nullable()->after('state');
            $table->string('country')->default('Sri Lanka')->after('postal_code');
            $table->json('specializations')->nullable()->after('services_offered');
            $table->boolean('accepts_insurance')->default(false)->after('prescription_required');
            $table->string('emergency_contact')->nullable()->after('contact_person_phone');
            $table->string('website')->nullable()->after('emergency_contact');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pharmacies', function (Blueprint $table) {
            $table->dropColumn([
                'pharmacy_name',
                'pharmacist_in_charge',
                'description',
                'address',
                'city',
                'state',
                'postal_code',
                'country',
                'specializations',
                'accepts_insurance',
                'emergency_contact',
                'website'
            ]);
        });
    }
};
