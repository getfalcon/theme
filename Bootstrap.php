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

class Bootstrap implements \yii\base\BootstrapInterface {

	/**
	 * @param \yii\base\Application $app
	 */
	public function bootstrap($app) {
		if ($app instanceof \falcon\core\frontend\Application) {
			$app->getView()->theme = new FrontendTheme();
		}
		if ($app instanceof \falcon\core\backend\Application) {
			$app->getView()->theme = new BackendTheme();
		}
	}
}