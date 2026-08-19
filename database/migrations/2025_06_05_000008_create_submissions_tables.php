<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('service_type')->nullable();
            $table->decimal('budget', 14, 2)->nullable();
            $table->text('message')->nullable();
            $table->enum('status', ['new', 'contacted', 'quoted', 'closed'])->default('new');
            $table->timestamps();
        });

        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->enum('status', ['new', 'replied', 'closed'])->default('new');
            $table->timestamps();
        });

        Schema::create('site_visits', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->date('preferred_date')->nullable();
            $table->string('preferred_time')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['requested', 'scheduled', 'completed', 'cancelled'])->default('requested');
            $table->timestamps();
        });

        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('topic')->nullable();
            $table->dateTime('scheduled_at')->nullable();
            $table->integer('duration_minutes')->default(30);
            $table->string('jitsi_room')->nullable();
            $table->enum('status', ['requested', 'confirmed', 'completed', 'cancelled'])->default('requested');
            $table->timestamps();
        });

        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('position');
            $table->string('trade')->nullable();
            $table->text('experience')->nullable();
            $table->string('cv_path')->nullable();
            $table->enum('status', ['new', 'reviewing', 'accepted', 'rejected'])->default('new');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
        Schema::dropIfExists('meetings');
        Schema::dropIfExists('site_visits');
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('quotes');
    }
};
