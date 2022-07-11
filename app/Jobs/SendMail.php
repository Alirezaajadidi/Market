<?php

namespace App\Jobs;

use App\Mail\ActivationUserAccount;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $user;
    protected $code;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user , $code)
    {
        $this->user = $user;
        $this->code = $code;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->user)->send(new ActivationUserAccount($this->user, $this->code));
    }
}
