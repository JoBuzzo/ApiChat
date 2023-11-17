<?php

use App\Models\User;
use Illuminate\Support\Str;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('avatar')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        User::create([
            'name' => 'João Lucas',
            'avatar' =>  "7xlsVTM2iBqVKNlXttYPO0iMtlyiDHDXzH3qZukk" . ".jpg",
            'email' => 'joaolucas@gmail.com',
            'password' => bcrypt("12345678"),
        ]); //1

        User::create([
            'name' => 'Pedro',
            'email' => 'pedro@gmail.com',
            'password' => bcrypt("12345678")
        ]); //2

        User::create([
            'name' => 'Heitor',
            'email' => 'heitor@gmail.com',
            'password' => bcrypt("12345678")
        ]); //3

        User::create([
            'name' => 'Laiza',
            'email' => 'laiza@gmail.com',
            'password' => bcrypt("12345678")
        ]); //4

        User::create([
            'name' => 'Vitória',
            'avatar' =>  "RqubVHTHcIsYJp8m0ktzYC8eL8t8DFWXn6kXc1Ky" . ".jpg",
            'email' => 'Vitória@gmail.com',
            'password' => bcrypt("12345678")
        ]); //5
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
