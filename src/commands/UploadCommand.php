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
    public function getAcl(): string
    {
        return (string)$this->acl;
    }

    /**
     * @param string $acl
     *
     * @return $this
     */
    public function withAcl(string $acl)
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
    public function withSource($source)
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
    public function withFilename(string $filename)
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
    public function withPartSize(int $partSize)
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
    public function withConcurrency(int $concurrency)
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
    public function withMupThreshold(int $mupThreshold)
    {
        $this->options['mup_threshold'] = $mupThreshold;

        return $this;
    }

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return $this->getParam('ContentType', '');
    }

    /**
     * @param string $contentType
     *
     * @return $this
     */
    public function withContentType(string $contentType)
    {
        return $this->withParam('ContentType', $contentType);
    }

    /**
     * @return string
     */
    public function getContentDisposition(): string
    {
        return $this->getParam('ContentDisposition', '');
    }

    /**
     * @param string $contentDisposition
     *
     * @return $this
     */
    public function withContentDisposition(string $contentDisposition)
    {
        return $this->withParam('ContentDisposition', $contentDisposition);
    }

    /**
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getParam(string $name, $default = null)
    {
        return $this->options['params'][$name] ?? $default;
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return $this
     */
    public function withParam(string $name, $value)
    {
        $this->options['params'][$name] = $value;

        return $this;
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
     * @internal used by the handlers
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
