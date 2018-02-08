<?php
/**
 * @package    falcon
 * @author     Hryvinskyi Volodymyr <volodymyr@hryvinskyi.com>
 * @copyright  Copyright (c) 2018. Hryvinskyi Volodymyr
 * @version    0.0.1-alpha.0.1
 */

namespace falcon\theme\app;

use falcon\core\components\ComponentRegistrar;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\View;
use yii\helpers\FileHelper;

class Theme extends \yii\base\Theme
{

    /**
     * @var View
     */
    protected $_view;

    /**
     * @var string
     */
    public $themeName;

    /**
     * @var array
     */
    public $assets = [];

    public function __construct(View $view, array $config = [])
    {
        $this->_view = $view;
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if ($this->themeName === null) {
            throw new InvalidConfigException('The "themeName" property must be set.');
        }

        $themePath = ComponentRegistrar::getPath(ComponentRegistrar::THEME, $this->themeName);

        $this->basePath = $themePath;
        $this->baseUrl = $themePath . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'web';

        $this->pathMap = [
            '@app/views' => $themePath . '/views',
            '@app/modules' => $themePath . '/modules',
            '@app/widgets' => $themePath . '/widgets',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function applyTo($path)
    {
        $pathMap = $this->pathMap;
        if (empty($pathMap)) {
            if (($basePath = $this->getBasePath()) === null) {
                throw new InvalidConfigException('The "basePath" property must be set.');
            }
            $pathMap = [Yii::$app->getBasePath() => [$basePath]];
        }

        $path = FileHelper::normalizePath($path);

        foreach ($pathMap as $from => $to) {
            $oldFrom = $from;
            $from = FileHelper::normalizePath(Yii::getAlias($from)) . DIRECTORY_SEPARATOR;

            if (strpos($path, $from) === 0) {
                $n = strlen($from);

                foreach ((array)$to as $item) {
                    $item = FileHelper::normalizePath(Yii::getAlias($item)) . DIRECTORY_SEPARATOR;
                    $file = $item . substr($path, $n);


                    if ($oldFrom == '@app/modules') {
                        $file = str_replace([
                            'views' . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR,
                            'views' . DIRECTORY_SEPARATOR . 'backend' . DIRECTORY_SEPARATOR
                        ], '', $file);
                    }

                    if (is_file($file)) {
                        return $file;
                    }
                }
            }
        }

        return $path;
    }

    /**
     * @param string $asset
     *
     * @return null|object
     */
    public function getAsset($asset)
    {
        if (isset($this->_view->assetManager->bundles[$asset])) {
            return (object)$this->_view->assetManager->bundles[$asset];
        }
        return null;
    }
}
