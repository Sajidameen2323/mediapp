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
        Schema::table('laboratories', function (Blueprint $table) {
            $table->string('name')->after('user_id');
            $table->text('description')->nullable()->after('name');
            $table->text('address')->after('description');
            $table->string('city')->after('address');
            $table->string('state')->after('city');
            $table->string('postal_code')->after('state');
            $table->string('country')->after('postal_code');
            $table->string('website')->nullable()->after('country');
            $table->string('accreditation')->nullable()->after('website');
            $table->string('emergency_contact')->nullable()->after('accreditation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laboratories', function (Blueprint $table) {
            $table->dropColumn([
                'name', 'description', 'address', 'city', 'state', 
                'postal_code', 'country', 'website', 'accreditation', 'emergency_contact'
            ]);
        });
    }
};
