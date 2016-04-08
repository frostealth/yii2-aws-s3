<?php

namespace frostealth\yii2\aws\s3\commands;

use frostealth\yii2\aws\s3\base\commands\ExecutableCommand;
use frostealth\yii2\aws\s3\interfaces\commands\HasBucket;

/**
 * Class GetPresignedUrlCommand
 *
 * @method string execute()
 *
 * @package frostealth\yii2\aws\s3\commands
 */
class GetPresignedUrlCommand extends ExecutableCommand implements HasBucket
{
    /** @var array */
    protected $args = [];

    /** @var mixed */
    protected $expires;

    /**
     * @return string
     */
    public function getBucket(): string
    {
        return $this->args['Bucket'] ?? '';
    }

    /**
     * @param string $bucket
     *
     * @return $this
     */
    public function setBucket(string $bucket)
    {
        $this->args['Bucket'] = $bucket;

        return $this;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->args['Key'] ?? '';
    }

    /**
     * @param string $filename
     *
     * @return $this
     */
    public function setFilename(string $filename)
    {
        $this->args['Key'] = $filename;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * @param int|string|\DateTime $expires
     *
     * @return $this
     */
    public function setExpires($expires)
    {
        $this->expires = $expires;

        return $this;
    }

    /**
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }
}
