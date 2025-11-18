<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Laravel\Sanctum\PersonalAccessToken;

class CheckAccesses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkAccesses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para eliminar los tokens de acceso personal si llevan mas de 60 min sin usar';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Obtenemos todos los tokens accesos personales y si son null o han sobré pasado 60 min los eliminamos
        $tokens = PersonalAccessToken::all();

        foreach ($tokens as $token) {
            $lastUsedAt = $token->last_used_at;
            $currentTime = Carbon::now();
            if ($lastUsedAt === null) {
                $created_at = $token->created_at;
                $differenceInMinutes = $currentTime->diffInMinutes($created_at);
                if ($differenceInMinutes > 360) {
                    $token->delete();
                }
            } else {
                $differenceInMinutes = $currentTime->diffInMinutes($lastUsedAt);
                if ($differenceInMinutes > 360) {
                    $token->delete();
                }
            }
        }

        return Command::SUCCESS;
    }
}
