<?php

namespace frostealth\yii2\aws\s3\interfaces\commands;

/**
 * Interface ExecutableCommand
 *
 * @package frostealth\yii2\aws\s3\interfaces\commands
 */
interface ExecutableCommand extends Command
{
    /**
     * @return mixed
     */
    public function execute();
}
