<?php

namespace frostealth\yii2\aws\s3\commands;

use frostealth\yii2\aws\s3\base\commands\ExecutableCommand;
use frostealth\yii2\aws\s3\interfaces\commands\HasBucket;

/**
 * Class GetUrlCommand
 *
 * @method string execute()
 *
 * @package frostealth\yii2\aws\s3\commands
 */
class GetUrlCommand extends ExecutableCommand implements HasBucket
{
    /** @var string */
    protected $bucket;

    /** @var string */
    protected $filename;

    /**
     * @return string
     */
    public function getBucket(): string
    {
        return (string)$this->bucket;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function inBucket(string $name)
    {
        $this->bucket = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return (string)$this->filename;
    }

    /**
     * @param string $filename
     *
     * @return $this
     */
    public function byFilename(string $filename)
    {
        $this->filename = $filename;

        return $this;
    }
}
