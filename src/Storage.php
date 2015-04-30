<?php

namespace frostealth\yii2\components\s3;

use Aws\S3\S3Client;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Class Storage
 *
 * @package frostealth\yii2\components\s3
 */
class Storage extends Component implements StorageInterface
{
    /** @var string */
    public $key;

    /** @var string */
    public $secret;

    /** @var string */
    public $bucket;

    /** @type string */
    public $cdnHostname;

    /** @var S3Client */
    private $client;

    /**
     * @throws \Exception
     */
    public function init()
    {
        if ($this->key === null || $this->secret === null) {
            throw new InvalidConfigException('S3 credentials isn\'t set.');
        }

        if ($this->bucket === null) {
            throw new InvalidConfigException('You must set bucket name.');
        }

        $this->client = S3Client::factory([
            'key' => $this->key,
            'secret' => $this->secret,
        ]);
    }

    /**
     * @return S3Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param string $filename
     * @param mixed $data
     * @param array $metadata
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function put($filename, $data, array $metadata = [])
    {
        $args = array_filter([
            'Bucket' => $this->bucket,
            'Key' => $filename,
            'Body' => $data,
            'Metadata' => $metadata,
        ]);

        return $this->getClient()->putObject($args);
    }

    /**
     * @param string $filename
     * @param string $saveAs
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function get($filename, $saveAs = null)
    {
        $args = array_filter([
            'Bucket' => $this->bucket,
            'Key' => $filename,
            'SaveAs' => $saveAs,
        ]);

        return $this->getClient()->getObject($args);
    }

    /**
     * @param string $filename
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function delete($filename)
    {
        return $this->getClient()->deleteObject([
            'Bucket' => $this->bucket,
            'Key' => $filename,
        ]);
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    public function getUrl($filename)
    {
        return $this->getClient()->getObjectUrl($this->bucket, $filename);
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    public function getCdnUrl($filename)
    {
        return $this->cdnHostname . '/' . $filename;
    }

    /**
     * @param string $prefix
     *
     * @return \Guzzle\Service\Resource\ResourceIteratorInterface
     */
    public function getList($prefix)
    {
        return $this->getClient()->getIterator('ListObjects', [
            'Bucket' => $this->bucket,
            'Prefix' => $prefix,
        ]);
    }

    /**
     * @param string $filename
     * @param string $sourceFile
     * @param array $metadata
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function putFile($filename, $sourceFile, array $metadata = [])
    {
        $args = array_filter([
            'Bucket' => $this->bucket,
            'Key' => $filename,
            'SourceFile' => $sourceFile,
            'Metadata' => $metadata,
        ]);

        return $this->getClient()->putObject($args);
    }
}
