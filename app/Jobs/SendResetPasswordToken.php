<?php

namespace App\Jobs;

use App\Mail\PasswordReset;
use App\Models\Player;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendResetPasswordToken implements ShouldQueue
{
    use Dispatchable,  Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Player $player,
        public string $token
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->player->email)->send(new PasswordReset(
            $this->token,
            $this->player->first_name,
            $this->player->last_name,
            $this->player->email
        ));
    }
}
