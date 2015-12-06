API
===

## Class frostealth\yii2\aws\s3\Storage

**Implements**: [frostealth\yii2\aws\s3\StorageInterface](https://github.com/frostealth/yii2-aws-s3/blob/master/src/StorageInterface.php)

**Inheritance**: [yii\base\Component](https://github.com/yiisoft/yii2/blob/master/framework/base/Component.php)

### Public properties
Property | Type | Required | Description
-------- | ---- | -------- | -----------
$credentials | [Aws\Credentials\CredentialsInterface]()\|[array](http://php.net/language.types.array)\|[callable](http://php.net/language.types.callback) | yes | Specifies the credentials used to sign requests.
$region | [string](http://php.net/language.types.string) | yes | Region to connect to. [Available regions](http://docs.aws.amazon.com/general/latest/gr/rande.html).
$bucket | [string](http://php.net/language.types.string) | yes | The bucket name.
$debug | [boolean](http://php.net/language.types.boolean)\|[array](http://www.php.net/language.types.array) | no | Set to true to display debug information when sending requests.
$options | [array](http://php.net/language.types.array) | no | [Other S3Client options](http://docs.aws.amazon.com/aws-sdk-php/v3/api/class-Aws.AwsClient.html#___construct).
$defaultAcl | [string](http://php.net/language.types.string) | no | Default ACL.
$cdnHostname | [string](http://php.net/language.types.string) | no | The full URL of the CloudFront.

### Constants
Constant | Value
-------- | -----
ACL_PRIVATE | 'private'
ACL_PUBLIC_READ | 'public-read'
ACL_PUBLIC_READ_WRITE | 'public-read-write'
ACL_AUTHENTICATED_READ | 'authenticated-read'
ACL_BUCKET_OWNER_READ | 'bucket-owner-read'
ALC_BUCKET_OWNER_FULL_CONTROL | 'bucket-owner-full-control'

### Public methods

#### put($filename, $data, $acl = null, array $options = [])
Adds an object to a bucket.

Argument | Type | Description
-------- | ---- | -----------
$filename | [string](http://php.net/language.types.string)
$data | mixed | Object data to upload. Can be a [Psr\Http\Message\StreamInterface](https://github.com/php-fig/http-message/blob/master/src/StreamInterface.php), php stream resource, or a string of data to upload.
$acl | [string](http://php.net/language.types.string) | ACL to apply to the object.
$options | [array](http://php.net/language.types.array) | [Other parameters](http://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#putobject).
_return_ | [Aws\ResultInterface](http://docs.aws.amazon.com/aws-sdk-php/v3/api/class-Aws.ResultInterface.html) | See [result syntax](http://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#putobject).

#### get($filename, $saveAs = null)
Retrieves an object from Amazon S3.

Argument | Type | Description
-------- | ---- | -----------
$filename | [string](http://php.net/language.types.string)
$saveAs | [string](http://php.net/language.types.string) | The path to a file on disk to save the object data.
_return_ | [Aws\ResultInterface](http://docs.aws.amazon.com/aws-sdk-php/v3/api/class-Aws.ResultInterface.html) | See [result syntax](http://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#getobject).

#### exist($filename, array $options = [])
Determines whether or not an object exists by name.

Argument | Type | Description
-------- | ---- | -----------
$filename | [string](http://php.net/language.types.string)
$options | [array](https://php.net/language.types.array) | Additional options available in the HeadObject operation (e.g., VersionId).
_return_ | [boolean](http://php.net/language.types.boolean)

#### delete($filename)
Removes an object from Amazon S3.

Argument | Type | Description
-------- | ---- | -----------
$filename | [string](http://php.net/language.types.string)
_return_ | [Aws\ResultInterface](http://docs.aws.amazon.com/aws-sdk-php/v3/api/class-Aws.ResultInterface.html) | See [result syntax](http://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#deleteobject).

#### getUrl($filename)
Returns the URL to an object identified by its bucket and key.

Argument | Type | Description
-------- | ---- | -----------
$filename | [string](http://php.net/language.types.string)
_return_ | [string](http://php.net/language.types.string)

#### getPresignedUrl($filename, $expires)
Create a pre-signed URL.

Argument | Type | Description
-------- | ---- | -----------
$filename | [string](http://php.net/language.types.string)
$expires | [string](http://php.net/language.types.string)\|[integer](http://php.net/language.types.integer)\|[DateTime](http://php.net/manual/class.datetime.php) | The time at which the URL should expire. This can be a Unix timestamp, a PHP DateTime object, or a string that can be evaluated by strtotime().
_return_ | [string](http://php.net/language.types.string)

#### getCdnUrl($filename)
Returns the CloundFront URL to an object.

Argument | Type | Description
-------- | ---- | -----------
$filename | [string](http://php.net/language.types.string)
_return_ | [string](http://php.net/language.types.string)

#### getList($prefix = null, array $options = [])
Returns some or all (up to 1000] of the objects in a bucket.

Argument | Type | Description
-------- | ---- | -----------
$prefix | [string](http://php.net/language.types.string) | Limits the response to keys that begin with the specified prefix.
$options | [array](http://php.net/language.types.string) | [Other parameters](http://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#listobjects).
_return_ | [Aws\ResultInterface](http://docs.aws.amazon.com/aws-sdk-php/v3/api/class-Aws.ResultInterface.html) | See [result syntax](http://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#listobjects).

#### upload($filename, $source, $acl = null, array $options = [])
Upload a file or stream to a bucket.

If the upload size exceeds the specified threshold, the upload will be performed using concurrent multipart uploads.

Argument | Type | Description
-------- | ---- | -----------
$filename | [string](http://php.net/language.types.string)
$source | mixed | File to upload. Can be a [Psr\Http\Message\StreamInterface](https://github.com/php-fig/http-message/blob/master/src/StreamInterface.php), php stream resource, or a path to a local file to upload.
$acl | [string](http://php.net/language.types.string) | ACL to apply to the object.
$options | [array](http://php.net/language.types.array) | Options used to configure the upload process. [See more](http://docs.aws.amazon.com/aws-sdk-php/v3/api/class-Aws.S3.S3Client.html#_upload).
_return_ | [Aws\ResultInterface](http://docs.aws.amazon.com/aws-sdk-php/v3/api/class-Aws.ResultInterface.html) | Returns the result of the upload.

#### uploadAsync($filename, $source, $concurrency = null, $partSize = null, $acl = null, array $options = [])
Upload a file or stream to a bucket asynchronously.

Argument | Type | Description
-------- | ---- | -----------
$filename | [string](http://php.net/language.types.string)
$source | mixed | File to upload. Can be a [Psr\Http\Message\StreamInterface](https://github.com/php-fig/http-message/blob/master/src/StreamInterface.php), php stream resource, or a path to a local file to upload.
$concurrency | [integer](http://php.net/language.types.integer) | Maximum number of concurrent UploadPart operations allowed during the multipart upload. Default 5.
$partSize | [integer](http://php.net/language.types.integer) | Part size, in bytes, to use when doing a multipart upload. This must between 5 MB and 5 GB, inclusive. Default 5242880 (512 MB).
$acl | [string](http://php.net/language.types.string) | ACL to apply to the object.
$options | [array](http://php.net/language.types.array) | Options used to configure the upload process. [See more](http://docs.aws.amazon.com/aws-sdk-php/v3/api/class-Aws.S3.MultipartUploader.html).
_return_ | [GuzzleHttp\Promise\PromiseInterface](https://docs.aws.amazon.com/aws-sdk-php/v3/api/class-GuzzleHttp.Promise.PromiseInterface.html) | Returns a [promise](http://docs.aws.amazon.com/aws-sdk-php/v3/guide/guide/promises.html) that will be fulfilled with the result of the upload.

#### getClient()
Returns the `S3Client` object.

Argument | Type | Description
-------- | ---- | -----------
_return_ | [Aws\S3\S3Client](http://docs.aws.amazon.com/aws-sdk-php/v3/api/class-Aws.S3.S3Client.html)
