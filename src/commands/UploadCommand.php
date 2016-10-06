<?php

namespace frostealth\yii2\aws\s3\commands;

use Aws\ResultInterface;
use frostealth\yii2\aws\s3\base\commands\ExecutableCommand;
use frostealth\yii2\aws\s3\base\commands\traits\Async;
use frostealth\yii2\aws\s3\interfaces\commands\Asynchronous;
use frostealth\yii2\aws\s3\interfaces\commands\HasAcl;
use frostealth\yii2\aws\s3\interfaces\commands\HasBucket;
use GuzzleHttp\Promise\PromiseInterface;

/**
 * Class UploadCommand
 *
 * @method ResultInterface|PromiseInterface execute()
 *
 * @package frostealth\yii2\aws\s3\commands
 */
class UploadCommand extends ExecutableCommand implements HasBucket, HasAcl, Asynchronous
{
    use Async;

    /** @var string */
    protected $bucket;

    /** @var string */
    protected $acl;

    /** @var mixed */
    protected $source;

    /** @var string */
    protected $filename;

    /** @var array */
    protected $options = [];

    /**
     * @return string
     */
    public function getBucket(): string
    {
        return (string)$this->bucket;
    }

    /**
     * @param string $bucket
     *
     * @return $this
     */
    public function setBucket(string $bucket)
    {
        $this->bucket = $bucket;

        return $this;
    }

    /**
     * @return string
     */
    public function getAcl(): string
    {
        return (string)$this->acl;
    }

    /**
     * @param string $acl
     *
     * @return $this
     */
    public function setAcl(string $acl)
    {
        $this->acl = $acl;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param mixed $source
     *
     * @return $this
     */
    public function setSource($source)
    {
        $this->source = $source;

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
    public function setFilename(string $filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return int
     */
    public function getPartSize(): int
    {
        return $this->options['part_size'] ?? 0;
    }

    /**
     * @param int $partSize
     *
     * @return $this
     */
    public function setPartSize(int $partSize)
    {
        $this->options['part_size'] = $partSize;

        return $this;
    }

    /**
     * @return int
     */
    public function getConcurrency(): int
    {
        return $this->options['concurrency'] ?? 0;
    }

    /**
     * @param int $concurrency
     *
     * @return $this
     */
    public function setConcurrency(int $concurrency)
    {
        $this->options['concurrency'] = $concurrency;

        return $this;
    }

    /**
     * @return int
     */
    public function getMupThreshold(): int
    {
        return $this->options['mup_threshold'] ?? 0;
    }

    /**
     * @param int $mupThreshold
     *
     * @return $this
     */
    public function setMupThreshold(int $mupThreshold)
    {
        $this->options['mup_threshold'] = $mupThreshold;

        return $this;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param callable $beforeUpload
     *
     * @return $this
     */
    public function beforeUpload(callable $beforeUpload)
    {
        $this->options['before_upload'] = $beforeUpload;

        return $this;
    }


    /**
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->args['Metadata'] ?? [];
    }

    /**
     * @param array $metadata
     *
     * @return $this
     */
    public function setMetadata(array $metadata)
    {
        $this->args['Metadata'] = $metadata;

        return $this;
    }

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return $this->args['ContentType'] ?? '';
    }

    /**
     * @param string $contentType
     *
     * @return $this
     */
    public function setContentType(string $contentType)
    {
        $this->args['ContentType'] = $contentType;

        return $this;
    }
}
