<?php

namespace frostealth\yii2\aws\s3\commands;

use Aws\ResultInterface;
use frostealth\yii2\aws\s3\base\commands\ExecutableCommand;
use frostealth\yii2\aws\s3\base\commands\traits\Async;
use frostealth\yii2\aws\s3\base\commands\traits\Options;
use frostealth\yii2\aws\s3\interfaces\commands\Asynchronous;
use frostealth\yii2\aws\s3\interfaces\commands\HasAcl;
use frostealth\yii2\aws\s3\interfaces\commands\HasBucket;
use frostealth\yii2\aws\s3\interfaces\commands\PlainCommand;
use GuzzleHttp\Promise\PromiseInterface;

/**
 * Class PutCommand
 *
 * @method ResultInterface|PromiseInterface execute()
 *
 * @package frostealth\yii2\aws\s3\commands
 */
class PutCommand extends ExecutableCommand implements PlainCommand, HasBucket, HasAcl, Asynchronous
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
    public function getFilename(): string
    {
        return $this->args['Key'] ?? '';
    }

    /**
     * @param string $filename
     *
     * @return $this
     */
    public function withFilename(string $filename)
    {
        $this->args['Key'] = $filename;

        return $this;
    }

    /**
     * @return string
     */
    public function getAcl(): string
    {
        return $this->args['ACL'] ?? '';
    }

    /**
     * @param string $acl
     *
     * @return $this
     */
    public function withAcl(string $acl)
    {
        $this->args['ACL'] = $acl;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->args['Body'] ?? null;
    }

    /**
     * @param mixed $body
     *
     * @return $this
     */
    public function withBody($body)
    {
        $this->args['Body'] = $body;

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
    public function withMetadata(array $metadata)
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
    public function withContentType(string $contentType)
    {
        $this->args['ContentType'] = $contentType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExpiration()
    {
        return $this->args['Expires'] ?? null;
    }

    /**
     * @param mixed $expires
     *
     * @return $this
     */
    public function withExpiration($expires)
    {
        $this->args['Expires'] = $expires;

        return $this;
    }

    /**
     * @internal used by the handlers
     *
     * @return string
     */
    public function getName(): string
    {
        return 'PutObject';
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
