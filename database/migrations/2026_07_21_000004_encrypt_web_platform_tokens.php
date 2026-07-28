<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::table('web_platforms')->whereNotNull('token')->orderBy('id')->each(function ($platform) {
            try {
                Crypt::decryptString($platform->token);
            } catch (\Throwable $e) {
                DB::table('web_platforms')->where('id', $platform->id)->update([
                    'token' => Crypt::encryptString($platform->token),
                ]);
            }
        });
    }

    public function down()
    {
        DB::table('web_platforms')->whereNotNull('token')->orderBy('id')->each(function ($platform) {
            try {
                $token = Crypt::decryptString($platform->token);
                DB::table('web_platforms')->where('id', $platform->id)->update(['token' => $token]);
            } catch (\Throwable $e) {
                // Already plain text.
            }
        });
    }
};
