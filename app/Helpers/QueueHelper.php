<?php

namespace App\Helpers;

/**
 * Class Queues
 * @package App\Helpers
 */
class QueueHelper
{
    /**
     * @constants
     */
    const PREFIX = 'queue';
    const TYPE_EMAIL = 'email';

    /**
     * Returns the queue name prefixed with the current environment
     *
     * @param $type
     *
     * @return string
     */
    public static function getName($type): string
    {
        return self::PREFIX . '-' . config('app.env') . '-' . $type;
    }
}
