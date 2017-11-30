<?php

namespace frostealth\yii2\aws\s3;

use Aws\ResultInterface;
use frostealth\yii2\aws\s3\interfaces\commands\Command;
use frostealth\yii2\aws\s3\interfaces\HandlerResolver;
use frostealth\yii2\aws\s3\interfaces\Service as ServiceInterface;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

/**
 * Class Service
 *
 * @property HandlerResolver $resolver
 *
 * @method ResultInterface  get(string $filename)
 * @method ResultInterface  put(string $filename, $body)
 * @method ResultInterface  delete(string $filename)
 * @method ResultInterface  upload(string $filename, $source)
 * @method ResultInterface  restore(string $filename, int $days)
 * @method ResultInterface  list(string $prefix)
 * @method bool             exist(string $filename)
 * @method string           getUrl(string $filename)
 * @method string           getPresignedUrl(string $filename, $expires)
 *
 * @package frostealth\yii2\aws\s3
 */
class Service extends Component implements ServiceInterface
{
    /** @var string */
    public $defaultBucket = '';

    /** @var string */
    public $defaultAcl = '';

    /** @var array S3Client config */
    protected $clientConfig = ['version' => '2006-03-01'];

    /** @var array */
    private $components = [];

    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after the object is initialized with the
     * given configuration.
     *
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (empty($this->clientConfig['credentials'])) {
            throw new InvalidConfigException('Credentials are not set.');
        }

        if (empty($this->clientConfig['region'])) {
            throw new InvalidConfigException('Region is not set.');
        }

        if (empty($this->defaultBucket)) {
            throw new InvalidConfigException('Default bucket name is not set.');
        }

        foreach ($this->defaultComponentDefinitions() as $name => $definition) {
            $this->components[$name] = $this->components[$name] ?? $definition;
        }
    }

    /**
     * Executes a command.
     *
     * @param \frostealth\yii2\aws\s3\interfaces\commands\Command $command
     *
     * @return mixed
     */
    public function execute(Command $command)
    {
        return $this->getComponent('bus')->execute($command);
    }

    /**
     * Creates a command with default params.
     *
     * @param string $commandClass
     *
     * @return \frostealth\yii2\aws\s3\interfaces\commands\Command
     */
    public function create(string $commandClass): Command
    {
        return $this->getComponent('builder')->build($commandClass);
    }

    /**
     * Returns command factory.
     *
     * @return \frostealth\yii2\aws\s3\CommandFactory
     */
    public function commands(): CommandFactory
    {
        return $this->getComponent('factory');
    }

    /**
     * Returns handler resolver.
     *
     * @return \frostealth\yii2\aws\s3\interfaces\HandlerResolver
     */
    public function getResolver(): HandlerResolver
    {
        return $this->getComponent('resolver');
    }

    /**
     * @param string $name
     * @param array  $params
     *
     * @return mixed
     */
    public function __call($name, $params)
    {
        if (method_exists($this->commands(), $name)) {
            $result = call_user_func_array([$this->commands(), $name], $params);

            return $result instanceof Command ? $this->execute($result) : $result;
        }

        return parent::__call($name, $params);
    }

    /**
     * @param \Aws\Credentials\CredentialsInterface|array|callable $credentials
     */
    public function setCredentials($credentials)
    {
        $this->clientConfig['credentials'] = $credentials;
    }

    /**
     * @param string $region
     */
    public function setRegion(string $region)
    {
        $this->clientConfig['region'] = $region;
    }

    /**
     * @param array|bool $debug
     */
    public function setDebug($debug)
    {
        $this->clientConfig['debug'] = $debug;
    }

    /**
     * @param array $options
     */
    public function setHttpOptions(array $options)
    {
        $this->clientConfig['http'] = $options;
    }

    /**
     * @param string|array|object $resolver
     */
    public function setResolver($resolver)
    {
        $this->setComponent('resolver', $resolver);
    }

    /**
     * @param string|array|object $bus
     */
    public function setBus($bus)
    {
        $this->setComponent('bus', $bus);
    }

    /**
     * @param string|array|object $builder
     */
    public function setBuilder($builder)
    {
        $this->setComponent('builder', $builder);
    }

    /**
     * @param string|array|object $factory
     */
    public function setFactory($factory)
    {
        $this->setComponent('factory', $factory);
    }

    /**
     * @param string $name
     *
     * @return object
     */
    protected function getComponent(string $name)
    {
        if (!is_object($this->components[$name])) {
            $this->components[$name] = $this->createComponent($name);
        }

        return $this->components[$name];
    }

    /**
     * @param string              $name
     * @param array|object|string $definition
     */
    protected function setComponent(string $name, $definition)
    {
        if (!is_object($definition)) {
            $definition = !is_array($definition) ? ['class' => $definition] : $definition;
            $definition = ArrayHelper::merge($this->defaultComponentDefinitions()[$name], $definition);
        }

        $this->components[$name] = $definition;
    }

    /**
     * @param string $name
     *
     * @return object
     * @throws \yii\base\InvalidConfigException
     */
    protected function createComponent(string $name)
    {
        $definition = $this->components[$name];
        $params = $this->getComponentParams($name);

        return \Yii::createObject($definition, $params);
    }

    /**
     * @return array
     */
    protected function defaultComponentDefinitions()
    {
        return [
            'client' => ['class' => 'Aws\S3\S3Client'],
            'resolver' => ['class' => 'frostealth\yii2\aws\s3\HandlerResolver'],
            'bus' => ['class' => 'frostealth\yii2\aws\s3\Bus'],
            'builder' => ['class' => 'frostealth\yii2\aws\s3\CommandBuilder'],
            'factory' => ['class' => 'frostealth\yii2\aws\s3\CommandFactory'],
        ];
    }

    /**
     * @param string $name
     *
     * @return array
     */
    protected function getComponentParams(string $name): array
    {
        switch ($name) {
            case 'client':
                $params = [$this->clientConfig];
                break;
            case 'resolver':
                $params = [$this->getComponent('client')];
                break;
            case 'bus':
                $params = [$this->getComponent('resolver')];
                break;
            case 'builder':
                $params = [$this->getComponent('bus'), $this->defaultBucket, $this->defaultAcl];
                break;
            case 'factory':
                $params = [$this->getComponent('builder')];
                break;
            default:
                $params = [];
        }

        return $params;
    }
}
