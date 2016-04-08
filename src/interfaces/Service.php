<?php

namespace frostealth\yii2\aws\s3\interfaces;

use frostealth\yii2\aws\s3\interfaces\commands\Command;

/**
 * Interface Service
 *
 * @package frostealth\yii2\aws\s3\interfaces
 */
interface Service
{
    /**
     * @param \frostealth\yii2\aws\s3\interfaces\commands\Command $command
     *
     * @return mixed
     */
    public function execute(Command $command);

    /**
     * @param string $commandClass
     *
     * @return \frostealth\yii2\aws\s3\interfaces\commands\Command
     */
    public function create(string $commandClass): Command;
}
