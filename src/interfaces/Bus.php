<?php

namespace frostealth\yii2\aws\s3\interfaces;

use frostealth\yii2\aws\s3\interfaces\commands\Command;

/**
 * Interface Bus
 *
 * @package frostealth\yii2\aws\s3\interfaces
 */
interface Bus
{
    /**
     * @param \frostealth\yii2\aws\s3\interfaces\commands\Command $command
     *
     * @return mixed
     */
    public function execute(Command $command);
}
