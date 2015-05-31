<?php

namespace frostealth\yii2\components\s3;

use Aws\S3\MultipartUploader;
use Aws\S3\S3Client;
use GuzzleHttp\Psr7;
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
    const ACL_PUBLIC_READ_WRITE = 'public-read-write';
    const ACL_AUTHENTICATED_READ = 'authenticated-read';
    const ACL_BUCKET_OWNER_READ = 'bucket-owner-read';
    const ALC_BUCKET_OWNER_FULL_CONTROL = 'bucket-owner-full-control';

    /** @type \Aws\Credentials\CredentialsInterface|array|callable */
    public $credentials;

    /** @type string */
    public $region;

    /** @type string */
    public $bucket;

    /** @type string */
    public $cdnHostname;

    /** @type string */
    public $defaultAcl;

    /** @type bool|array */
    public $debug;

    /** @type array */
    public $options = [];

    /** @type S3Client */
    private $client;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (empty($this->credentials)) {
            throw new InvalidConfigException('S3 credentials isn\'t set.');
        }

        if (empty($this->region)) {
            throw new InvalidConfigException('Region isn\'t set.');
        }

        if (empty($this->bucket)) {
            throw new InvalidConfigException('You must set bucket name.');
        }

        $args = $this->prepareArgs($this->options, [
            'version' => '2006-03-01',
            'region' => $this->region,
            'credentials' => $this->credentials,
            'debug' => $this->debug,
        ]);

        $this->client = new S3Client($args);
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
     * @return \Aws\ResultInterface
     */
    public function put($filename, $data, $acl = null, array $options = [])
    {
        $args = $this->prepareArgs($options, [
            'Bucket' => $this->bucket,
            'Key' => $filename,
            'Body' => $data,
            'ACL' => !empty($acl) ? $acl : $this->defaultAcl,
        ]);

        return $this->execute('PutObject', $args);
    }

    /**
     * @param string $filename
     * @param string $saveAs
     *
     * @return \Aws\ResultInterface
     */
    public function get($filename, $saveAs = null)
    {
        $args = $this->prepareArgs([
            'Bucket' => $this->bucket,
            'Key' => $filename,
            'SaveAs' => $saveAs,
        ]);

        return $this->execute('GetObject', $args);
    }

    /**
     * @param string $filename
     * @param array $options
     *
     * @return bool
     */
    public function exist($filename, array $options = [])
    {
        return $this->getClient()->doesObjectExist($this->bucket, $filename, $options);
    }

    /**
     * @param string $filename
     *
     * @return \Aws\ResultInterface
     */
    public function delete($filename)
    {
        return $this->execute('DeleteObject', [
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
     * @param string|int|\DateTime $expires
     *
     * @return string
     */
    public function getPresignedUrl($filename, $expires)
    {
        $command = $this->getClient()->getCommand('GetObject', ['Bucket' => $this->bucket, 'Key' => $filename]);
        $request = $this->getClient()->createPresignedRequest($command, $expires);

        return (string) $request->getUri();
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
     * @param array $options
     *
     * @return \Aws\ResultInterface
     */
    public function getList($prefix = null, array $options = [])
    {
        $args = $this->prepareArgs($options, [
            'Bucket' => $this->bucket,
            'Prefix' => $prefix,
        ]);

        return $this->execute('ListObjects', $args);
    }

    /**
     * @param string $filename
     * @param mixed $source
     * @param string $acl
     * @param array $options
     *
     * @return \Aws\ResultInterface
     */
    public function upload($filename, $source, $acl = null, array $options = [])
    {
        return $this->getClient()->upload(
            $this->bucket,
            $filename,
            $source,
            !empty($acl) ? $acl : $this->defaultAcl,
            $options
        );
    }

    /**
     * @param string $filename
     * @param mixed $source
     * @param int $concurrency
     * @param int $partSize
     * @param string $acl
     * @param array $options
     *
     * @return \Aws\ResultInterface
     */
    public function multipartUpload(
        $filename,
        $source,
        $concurrency = null,
        $partSize = null,
        $acl = null,
        array $options = []
    ) {
        $args = $this->prepareArgs($options, [
            'bucket' => $this->bucket,
            'acl' => !empty($acl) ? $acl : $this->defaultAcl,
            'key' => $filename,
            'concurrency' => $concurrency,
            'part-size' => $partSize,
        ]);

        $uploader = new MultipartUploader($this->getClient(), $source, $args);

        return $uploader->upload();
    }

    /**
     * @param string $name
     * @param array $args
     *
     * @return \Aws\ResultInterface
     */
    protected function execute($name, array $args)
    {
        $command = $this->getClient()->getCommand($name, $args);

        return $this->getClient()->execute($command);
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
