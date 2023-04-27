<?php

namespace App\Mail;

use App\Helpers\QueueHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AbstractEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * setQueue
     */
    protected function setQueue()
    {
        $this->onQueue(QueueHelper::getName(QueueHelper::TYPE_EMAIL));
    }
}
