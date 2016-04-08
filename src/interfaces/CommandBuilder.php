<?php

namespace frostealth\yii2\aws\s3\interfaces;

use frostealth\yii2\aws\s3\interfaces\commands\Command;

/**
 * Interface CommandBuilder
 *
 * @package frostealth\yii2\aws\s3\interfaces
 */
interface CommandBuilder
{
    /**
     * @param string $commandClass
     *
     * @return \frostealth\yii2\aws\s3\interfaces\commands\Command
     */
    public function build(string $commandClass): Command;
}
