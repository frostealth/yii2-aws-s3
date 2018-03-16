<?php

namespace frostealth\yii2\aws\s3\commands;

use Aws\ResultInterface;
use frostealth\yii2\aws\s3\base\commands\ExecutableCommand;
use frostealth\yii2\aws\s3\base\commands\traits\Async;
use frostealth\yii2\aws\s3\base\commands\traits\Options;
use frostealth\yii2\aws\s3\interfaces\commands\Asynchronous;
use frostealth\yii2\aws\s3\interfaces\commands\HasBucket;
use frostealth\yii2\aws\s3\interfaces\commands\PlainCommand;
use GuzzleHttp\Promise\PromiseInterface;

/**
 * Class ListCommand
 *
 * @method ResultInterface|PromiseInterface execute()
 *
 * @package frostealth\yii2\aws\s3\commands
 */
class ListTwoCommand extends ExecutableCommand implements PlainCommand, HasBucket, Asynchronous
{
    use Async;
    use Options;

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
     * @param string $name
     *
     * @return $this
     */
    public function inBucket(string $name)
    {
        $this->args['Bucket'] = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->args['Prefix'] ?? '';
    }

    /**
     * @param string $prefix
     *
     * @return $this
     */
    public function byPrefix(string $prefix)
    {
        $this->args['Prefix'] = $prefix;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxKeys(): int
    {
        return $this->args['MaxKeys'] ?? 1000;
    }

    /**
     * @param int $limit
     *
     * @return $this
     */
    public function byMaxKeys(int $limit)
    {
        $this->args['MaxKeys'] = $limit;

        return $this;
    }

    /**
     * @return int
     */
    public function getStartAfter(): int
    {
        return $this->args['StartAfter'] ?? '';
    }

    /**
     * @param string $after
     *
     * @return $this
     */
    public function byStartAfter(string $after)
    {
        $this->args['StartAfter'] = $after;

        return $this;
    }

    /**
     * @internal used by the handlers
     *
     * @return string
     */
    public function getName(): string
    {
        return 'ListObjectsV2';
    }

    /**
     * @internal used by the handlers
     *
     * @return array
     */
    public function toArgs(): array
    {
        return array_replace($this->options, $this->args);
    }
}
