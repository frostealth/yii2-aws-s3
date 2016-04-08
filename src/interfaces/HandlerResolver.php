<?php

namespace frostealth\yii2\aws\s3\interfaces;

use frostealth\yii2\aws\s3\interfaces\commands\Command;
use frostealth\yii2\aws\s3\interfaces\handlers\Handler;

/**
 * Interface HandlerResolver
 *
 * @package frostealth\yii2\aws\s3\interfaces
 */
interface HandlerResolver
{
    /**
     * @param \frostealth\yii2\aws\s3\interfaces\commands\Command $command
     *
     * @return \frostealth\yii2\aws\s3\interfaces\handlers\Handler
     */
    public function resolve(Command $command): Handler;

    /**
     * @param string $commandClass
     * @param mixed  $handler
     */
    public function bindHandler(string $commandClass, $handler);
}
