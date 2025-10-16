<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->timestamp('time_appointment');
            $table->string('status')->default('pending');
            $table->string('doctor_note')->default('Tidak ada');
            $table->string('patient_note')->default('Tidak ada');
            $table->string('cancellation_reason')->default('');
            $table->enum('cancelled_by', ['patient', 'doctor'])->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};
