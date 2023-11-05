<?php

namespace Juzaweb\CMS\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Juzaweb\Backend\Models\EmailList;
use Juzaweb\CMS\Support\SendEmail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected EmailList $mail;

    /**
     * Create a new job instance.
     *
     * @param  EmailList  $mail
     * @return void
     */
    public function __construct(EmailList $mail)
    {
        $this->mail = $mail;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle(): void
    {
        (new SendEmail($this->mail))->send();
    }
}
