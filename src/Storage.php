<?php

namespace frostealth\yii2\components\s3;

use Aws\S3\Model\MultipartUpload\AbstractTransfer;
use Aws\S3\Model\MultipartUpload\UploadBuilder;
use Aws\S3\S3Client;
use Guzzle\Http\EntityBody;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Class Storage
 *
 * @package frostealth\yii2\components\s3
 */
class Storage extends Component implements StorageInterface
{
    const ACL_PRIVATE = 'private';
    const ACL_PUBLIC_READ = 'public-read';
    const ACL_AUTHENTICATED_READ = 'authenticated-read';

    /** @var string */
    public $key;

    /** @var string */
    public $secret;

    /** @var string */
    public $bucket;

    /** @type string */
    public $cdnHostname;

    /** @type string */
    public $defaultAcl;

    /** @var S3Client */
    private $client;

    /**
     * @throws InvalidConfigException
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
     * @param string $acl
     * @param array $options
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function put($filename, $data, $acl = null, array $options = [])
    {
        $args = $this->prepareArgs($options, [
            'Bucket' => $this->bucket,
            'Key' => $filename,
            'Body' => $data,
            'ACL' => !empty($acl) ? $acl : $this->defaultAcl,
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
        $args = $this->prepareArgs([
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
     * @param string $expires
     * @param array $options
     *
     * @return string
     */
    public function getUrl($filename, $expires = null, array $options = [])
    {
        return $this->getClient()->getObjectUrl($this->bucket, $filename, $expires, $options);
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
    public function getList($prefix = null)
    {
        $args = $this->prepareArgs([
            'Bucket' => $this->bucket,
            'Prefix' => $prefix,
        ]);

        return $this->getClient()->getIterator('ListObjects', $args);
    }

    /**
     * @param string $filename
     * @param mixed $source
     * @param string $acl
     * @param array $options
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function upload($filename, $source, $acl = null, array $options = [])
    {
        $body = EntityBody::factory($source);

        if ($body->getSize() < AbstractTransfer::MIN_PART_SIZE) {
            $result = $this->put($filename, $body, $acl, $options);
        } else {
            $concurrency = $body->getWrapper() == 'plainfile' ? 3 : 1;
            $result = $this->multipartUpload($filename, $body, $concurrency, null, $acl, $options);
        }

        return $result;
    }

    /**
     * @param string $filename
     * @param mixed $source
     * @param int $concurrency
     * @param int $minPartSize
     * @param string $acl
     * @param array $options
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function multipartUpload(
        $filename,
        $source,
        $concurrency = 3,
        $minPartSize = null,
        $acl = null,
        array $options = []
    ) {
        $builder = UploadBuilder::newInstance();

        $builder->setClient($this->getClient());
        $builder->setBucket($this->bucket);
        $builder->setKey($filename);
        $builder->setSource($source);
        $builder->setConcurrency($concurrency);
        $builder->setMinPartSize($minPartSize);
        $builder->addOptions($options);

        if (!empty($acl) || !empty($this->defaultAcl)) {
            $builder->setOption('ACL', !empty($acl) ? $acl : $this->defaultAcl);
        }

        return $builder->build()->upload();
    }

    /**
     * @param array $a
     *
     * @return array
     */
    protected function prepareArgs(array $a)
    {
        $result = [];
        $args = func_get_args();

        foreach ($args as $item) {
            $item = array_filter($item);
            $result = array_replace($result, $item);
        }

        return $result;
    }
}
