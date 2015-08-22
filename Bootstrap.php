<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace wartron\yii2account\rbac;

use wartron\yii2account\rbac\components\DbManager;
use wartron\yii2account\rbac\components\ManagerInterface;
use wartron\yii2account\Module as AccountModule;
use yii\base\Application;
use yii\base\BootstrapInterface;

/**
 * Bootstrap class registers translations and needed application components.
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Bootstrap implements BootstrapInterface
{
    /** @inheritdoc */
    public function bootstrap($app)
    {
        // register translations
        if (!isset($app->get('i18n')->translations['rbac*'])) {
            $app->get('i18n')->translations['rbac*'] = [
                'class'    => 'yii\i18n\PhpMessageSource',
                'basePath' => __DIR__ . '/messages',
            ];
        }

        if ($this->checkRbacModuleInstalled($app)) {
            // register auth manager
            if (!$this->checkAuthManagerConfigured($app)) {
                $app->set('authManager', [
                    'class' => DbManager::className(),
                ]);
            }

            // if wartron\yii2account/user extension is installed, copy admin list from there
            if ($this->checkUserModuleInstalled($app)) {
                $app->getModule('rbac')->admins = $app->getModule('account')->admins;
            }
        }
    }

    /**
     * Verifies that dektrium/yii2-rbac is installed and configured.
     * @param  Application $app
     * @return bool
     */
    protected function checkRbacModuleInstalled(Application $app)
    {
        return $app->hasModule('rbac') && $app->getModule('rbac') instanceof Module;
    }

    /**
     * Verifies that dektrium/yii2-user is installed and configured.
     * @param  Application $app
     * @return bool
     */
    protected function checkAccountModuleInstalled(Application $app)
    {
        return $app->hasModule('account') && $app->getModule('account') instanceof AccountModule;
    }

    /**
     * Verifies that authManager component is configured.
     * @param  Application $app
     * @return bool
     */
    protected function checkAuthManagerConfigured(Application $app)
    {
        return $app->authManager instanceof ManagerInterface;
    }
}
