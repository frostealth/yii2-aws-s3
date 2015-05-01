# Yii2 AWS S3

An Amazon S3Client wrapper as Yii2 component.

Yii2 AWS S3 can only work with **one** bucket per a component configuration.

Component currently supports CloudFront (getting a CDN url for an object in S3 bucket).

## Installation
1. The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

    Either run `php composer.phar require frostealth/yii2-aws-s3 "0.1.*"`

    or add `"frostealth/yii2-aws-s3": "0.1.*"` to the require section of your `composer.json` file.
2. Add component to `config/main.php`

    ```php
    'components' => [
        // ...
        's3bucket' => [
            'class' => \frostealth\yii2\components\s3\Storage::className(),
            'key' => 'your aws s3 key',
            'secret' => 'your aws s3 secret',
            'bucket' => 'your aws s3 bucket',
            'cdnHostname' => 'http://example.cloudfront.net',
        ],
        // ...
    ],
    ```

## Usage

### Uploading objects
```php
// creating an object
$data = ['one', 'two', 'three'];
Yii::$app->get('s3bucket')->put('path/to/s3object.ext', Json::encode($data));

// uploading an object by streaming the contents of a stream
$resource = fopen('/path/to/local/file.ext', 'r+');
Yii::$app->get('s3bucket')->put('path/to/s3object.ext', $resource);
```

### Uploading files
```php
Yii::$app->get('s3bucket')->putFile('path/to/s3object.ext', '/path/to/local/file.ext');
```

### Reading objects
```php
/** @type \Guzzle\Service\Resource\Model $response */
$result = Yii::$app->get('s3bucket')->get('path/to/s3object.ext');
$data = (string) $result['Body']; // the 'Body' value of the result is an Guzzle\Http\EntityBody object
```

### Saving objects to a file
```php
Yii::$app->get('s3bucket')->get('path/to/s3object.ext', '/path/to/local/file.ext');
```

### Deleting objects
```php
Yii::$app->get('s3bucket')->delete('path/to/s3object.ext');
```

### Getting a plain URL
```php
Yii::$app->get('s3bucket')->getUrl('path/to/s3object.ext');
```

### Getting a CDN URL
```php
Yii::$app->get('s3bucket')->getCdnUrl('path/to/s3object.ext');
```

### Listing objects
```php
$iterator = Yii::$app->get('s3bucket')->getList('path');
foreach ($iterator as $object) {
    echo $object['key'] . PHP_EOL;
}
```