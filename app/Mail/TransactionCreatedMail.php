<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class TransactionCreatedMail extends AbstractEmail
{
    use Queueable, SerializesModels;

    /**
     * @var $transactionName
     */
    private $transactionName;
    public $user;


    /**
     * TransactionCreatedMail constructor.
     *
     * @param $transactionName
     * @param $receiver
     */
    public function __construct($transactionName, $user)
    {
        $this->setQueue();
        $this->transactionName = $transactionName;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): TransactionCreatedMail
    {
        return $this->markdown('emails.transaction.created',
            ['transactionName' => $this->transactionName]);
    }
}
