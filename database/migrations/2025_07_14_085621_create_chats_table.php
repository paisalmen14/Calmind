<?php
// database/migrations/YYYY_MM_DD_XXXXXX_create_chats_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id(); //
            $table->foreignId('consultation_id')->nullable()->after('id')->constrained('consultations')->onDelete('cascade'); //
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade'); //
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade'); //
            $table->text('message'); //
            $table->timestamp('read_at')->nullable(); //
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};