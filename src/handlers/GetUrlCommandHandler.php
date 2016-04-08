<?php

namespace frostealth\yii2\aws\s3\handlers;

use frostealth\yii2\aws\s3\base\handlers\Handler;
use frostealth\yii2\aws\s3\commands\GetUrlCommand;

/**
 * Class GetUrlCommandHandler
 *
 * @package frostealth\yii2\aws\s3\handlers
 */
final class GetUrlCommandHandler extends Handler
{
    /**
     * @param \frostealth\yii2\aws\s3\commands\GetUrlCommand $command
     *
     * @return string
     */
    public function handle(GetUrlCommand $command): string
    {
        return $this->s3Client->getObjectUrl($command->getBucket(), $command->getFilename());
    }
}
