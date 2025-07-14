<?php
// database/migrations/YYYY_MM_DD_XXXXXX_create_payment_confirmations_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_confirmations', function (Blueprint $table) {
            $table->id(); //
            $table->foreignId('consultation_id')->after('id')->constrained('consultations')->onDelete('cascade'); //
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); //
            $table->foreignId('psychologist_id')->nullable()->after('user_id')->constrained('users')->onDelete('set null'); //
            $table->string('transaction_id')->unique(); //
            $table->decimal('amount', 15, 2); //
            $table->date('payment_date'); //
            $table->string('proof_path'); //
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); //
            $table->text('admin_notes')->nullable(); //
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_confirmations');
    }
};