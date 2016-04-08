<?php

namespace frostealth\yii2\aws\s3\handlers;

use frostealth\yii2\aws\s3\base\handlers\Handler;
use frostealth\yii2\aws\s3\commands\ExistCommand;

/**
 * Class ExistCommandHandler
 *
 * @package frostealth\yii2\aws\s3\handlers
 */
final class ExistCommandHandler extends Handler
{
    /**
     * @param \frostealth\yii2\aws\s3\commands\ExistCommand $command
     *
     * @return bool
     */
    public function handle(ExistCommand $command): bool
    {
        return $this->s3Client->doesObjectExist(
            $command->getBucket(),
            $command->getFilename(),
            $command->getOptions()
        );
    }
}
