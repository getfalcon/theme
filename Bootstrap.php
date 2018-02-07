<?php
/**
 * @package    falcon
 * @author     Hryvinskyi Volodymyr <volodymyr@hryvinskyi.com>
 * @copyright  Copyright (c) 2018. Hryvinskyi Volodymyr
 * @version    0.0.1-alpha.0.1
 */

namespace falcon\theme;

use app\themes\falcon\base\Theme as FrontendTheme;
use app\themes\falcon\backend\Theme as BackendTheme;
use yii\base\InvalidConfigException;

class Bootstrap implements \yii\base\BootstrapInterface {

	/**
	 * @param \yii\base\Application $app
     * @throws InvalidConfigException
     */
	public function bootstrap($app) {
        $container = \Yii::$container;

        if ($app instanceof \falcon\frontend\app\Application) {
            $app->getView()->theme = $container->get(FrontendTheme::class, [$app->getView()]);
		}
        if ($app instanceof \falcon\backend\app\Application) {
            $app->getView()->theme = $container->get(BackendTheme::class, [$app->getView()]);
		}
	}
}