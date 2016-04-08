<?php

namespace frostealth\yii2\aws\s3\interfaces\commands;

/**
 * Interface HasBucket
 *
 * @package frostealth\yii2\aws\s3\interfaces\commands
 */
interface HasBucket
{
    /**
     * @param string $bucket
     */
    public function setBucket(string $bucket);
}
