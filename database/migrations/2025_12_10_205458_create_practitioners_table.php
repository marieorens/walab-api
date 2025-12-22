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
        Schema::create('practitioners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            
            // Registration (Required)
            $table->string('order_number', 100)->unique();
            $table->string('certificate_url', 255)->nullable();
            $table->string('profession', 100); // Changé de enum à string pour accepter des professions personnalisées
            
            // Specialties
            $table->string('main_specialty', 100)->nullable();
            $table->json('secondary_specialties')->nullable();
            
            // Experience & Education
            $table->enum('years_experience', ['<1', '1-5', '5-10', '10-20', '20+'])->nullable();
            $table->string('main_diploma', 255)->nullable();
            $table->json('documents_urls')->nullable();
            
            // Presentation
            $table->text('bio')->nullable();
            $table->string('profile_photo', 255)->nullable();
            $table->json('languages_spoken')->nullable();
            
            // Affiliation & Location
            $table->string('affiliated_institution', 255)->nullable();
            $table->text('office_address')->nullable();
            $table->string('location', 255)->nullable();
            $table->string('professional_phone', 20)->nullable();
            $table->string('professional_email', 100)->nullable();
            
            // Availability & Fees
            $table->json('availability')->nullable();
            $table->decimal('consultation_fee', 10, 2)->nullable();
            
            // Validation
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('validated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('validated_at')->nullable();
            $table->text('rejection_reason')->nullable();
            
            // Profile & Visibility
            $table->integer('profile_completion')->default(20);
            $table->boolean('is_active')->default(false);
            $table->boolean('is_featured')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practitioners');
    }
};
