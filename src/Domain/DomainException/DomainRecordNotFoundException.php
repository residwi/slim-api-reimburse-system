<?php
declare(strict_types=1);

namespace App\Domain\DomainException;

class DomainRecordNotFoundException extends DomainException
{
    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }
}
