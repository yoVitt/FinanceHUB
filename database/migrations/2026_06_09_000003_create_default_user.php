<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        if (! DB::table('users')->where('email', 'user@gmail.com')->exists()) {
            DB::table('users')->insert([
                'name' => 'user',
                'email' => 'user@gmail.com',
                'password' => Hash::make('user'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('users')->where('email', 'user@gmail.com')->delete();
    }
};
