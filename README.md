# Yii2 AWS S3

An Amazon S3 component for Yii2.

[![License](https://poser.pugx.org/frostealth/yii2-aws-s3/license)](https://github.com/frostealth/yii2-aws-s3/blob/2.x/LICENSE.md)
[![Latest Stable Version](https://poser.pugx.org/frostealth/yii2-aws-s3/v/stable)](https://packagist.org/packages/frostealth/yii2-aws-s3)
[![Total Downloads](https://poser.pugx.org/frostealth/yii2-aws-s3/downloads)](https://packagist.org/packages/frostealth/yii2-aws-s3)
[![Latest Unstable Version](https://poser.pugx.org/frostealth/yii2-aws-s3/v/unstable)](https://packagist.org/packages/frostealth/yii2-aws-s3)

## Installation

1. Run the [Composer](http://getcomposer.org/download/) command to install the latest stable version:

    ```bash
    composer require frostealth/yii2-aws-s3 ~2.0@stable
    ```

2. Add the component to `config/main.php`

    ```php
    'components' => [
        // ...
        's3' => [
            'class' => 'frostealth\yii2\aws\s3\Service',
            'credentials' => [ // Aws\Credentials\CredentialsInterface|array|callable
                'key' => 'my-key',
                'secret' => 'my-secret',
            ],
            'region' => 'my-region',
            'defaultBucket' => 'my-bucket',
            'defaultAcl' => 'public-read',
        ],
        // ...
    ],
    ```

## Basic usage

### Usage the command factory and additional params

```php
/** @var \frostealth\yii2\aws\s3\Service $s3 */
$s3 = Yii::$app->get('s3');

/** @var \Aws\ResultInterface $result */
$result = $s3->commands()->get('filename.ext')->saveAs('/path/to/local/file.ext')->execute();

$result = $s3->commands()->put('filename.ext', 'body')->setContentType('text/plain')->execute();

$result = $s3->commands()->delete('filename.ext')->execute();

$result = $s3->commands()->upload('filename.ext', '/path/to/local/file.ext')->setAcl('private')->execute();

$result = $s3->commands()->restore('filename.ext', $days = 7)->execute();

$isExisting = $s3->commands()->exist('filename.ext')->execute();

$url = $s3->commands()->getUrl('filename.ext')->execute();

$signedUrl = $s3->commands()->getPresignedUrl('filename.ext', '+2 days')->execute();
```

### Short syntax

```php
/** @var \frostealth\yii2\aws\s3\Service $s3 */
$s3 = Yii::$app->get('s3');

/** @var \Aws\ResultInterface $result */
$result = $s3->get('filename.ext');

$result = $s3->put('filename.ext', 'body');

$result = $s3->delete('filename.ext');

$result = $s3->upload('filename.ext', '/path/to/local/file.ext');

$result = $s3->restore('filename.ext', $days = 7);

$isExisting = $s3->exist('filename.ext');

$url = $s3->getUrl('filename.ext');

$signedUrl = $s3->getPresignedUrl('filename.ext', '+2 days');
```

### Asynchronous execution

```php
/** @var \frostealth\yii2\aws\s3\Service $s3 */
$s3 = Yii::$app->get('s3');

/** @var \GuzzleHttp\Promise\PromiseInterface $promise */
$promise = $s3->commands()->get('filename.ext')->async()->execute();

$promise = $s3->commands()->put('filename.ext', 'body')->async()->execute();

$promise = $s3->commands()->delete('filename.ext')->async()->execute();

$promise = $s3->commands()->upload('filename.ext', 'source')->async()->execute();
```

## License

Yii2 AWS S3 is licensed under the MIT License.
See the [LICENSE.md](LICENSE.md) file for more information.