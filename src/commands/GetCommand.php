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
 * Class GetCommand
 *
 * @method ResultInterface|PromiseInterface execute()
 *
 * @package frostealth\yii2\aws\s3\commands
 */
class GetCommand extends ExecutableCommand implements PlainCommand, HasBucket, Asynchronous
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
     * @param string $value
     *
     * @return $this
     */
    public function saveAs(string $value)
    {
        $this->args['SaveAs'] = $value;

        return $this;
    }

    /**
     * @param string $ifMatch
     *
     * @return $this
     */
    public function ifMatch(string $ifMatch)
    {
        $this->args['IfMatch'] = $ifMatch;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'GetObject';
    }

    /**
     * @return array
     */
    public function toArgs(): array
    {
        return array_replace($this->options, $this->args);
    }
}
