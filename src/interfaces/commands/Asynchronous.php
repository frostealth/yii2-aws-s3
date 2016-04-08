<?php

namespace frostealth\yii2\aws\s3\interfaces\commands;

/**
 * Interface Asynchronous
 *
 * @package frostealth\yii2\aws\s3\interfaces\commands
 */
interface Asynchronous
{
    /**
     * @param bool $async
     */
    public function async(bool $async = true);

    /**
     * @return bool
     */
    public function isAsync(): bool;
}
