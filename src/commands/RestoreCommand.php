<?php

namespace frostealth\yii2\aws\s3\commands;

use Aws\ResultInterface;
use frostealth\yii2\aws\s3\base\commands\ExecutableCommand;
use frostealth\yii2\aws\s3\base\commands\traits\Async;
use frostealth\yii2\aws\s3\interfaces\commands\Asynchronous;
use frostealth\yii2\aws\s3\interfaces\commands\HasBucket;
use frostealth\yii2\aws\s3\interfaces\commands\PlainCommand;
use GuzzleHttp\Promise\PromiseInterface;

/**
 * Class RestoreCommand
 *
 * @method ResultInterface|PromiseInterface execute()
 *
 * @package frostealth\yii2\aws\s3\commands
 */
class RestoreCommand extends ExecutableCommand implements PlainCommand, HasBucket, Asynchronous
{
    use Async;

    /** @var array */
    protected $args = [];

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
     * @return int lifetime of the active copy in days
     */
    public function getDays(): int
    {
        return $this->args['Days'] ?? 0;
    }

    /**
     * @param int $days lifetime of the active copy in days
     *
     * @return $this
     */
    public function setDays(int $days)
    {
        $this->args['Days'] = $days;

        return $this;
    }

    /**
     * @return string
     */
    public function getVersionId(): string
    {
        return $this->args['VersionId'] ?? '';
    }

    /**
     * @param string $versionId
     *
     * @return $this
     */
    public function setVersionId(string $versionId)
    {
        $this->args['VersionId'] = $versionId;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'RestoreObject';
    }

    /**
     * @return array
     */
    public function toArgs(): array
    {
        return $this->args;
    }
}
