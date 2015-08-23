<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

/**
 * @var $this yii\web\View
 */

use wartron\yii2widgets\urlactive\Nav;

?>

<?= Nav::widget([
    'options' => [
        'class' => 'nav-tabs'
    ],
    'items' => [
        [
            'label'     =>  Yii::t('rbac', 'Accounts'),
            'url'       =>  ['/account/admin/index'],
            'visible'   =>  isset(Yii::$app->extensions['wartron/yii2-account']),
        ],
        [
            'label'     =>  Yii::t('rbac', 'Roles'),
            'url'       =>  ['/rbac/role/index'],
            'urlActive' => [
                ['/rbac/role/view'],
                ['/rbac/role/update'],
                ['/rbac/role/create'],
            ]
        ],
        [
            'label'     =>  Yii::t('rbac', 'Permissions'),
            'url'       =>  ['/rbac/permission/index'],
            'urlActive' => [
                ['/rbac/permission/view'],
                ['/rbac/permission/update'],
                ['/rbac/permission/create'],
            ]
        ],
        [
            'label'     =>  Yii::t('rbac', 'Create'),
            'items'     =>  [
                [
                    'label'     =>  Yii::t('rbac', 'New user'),
                    'url'       =>  ['/account/admin/create'],
                    'visible'   =>  isset(Yii::$app->extensions['wartron/yii2-account']),
                ],
                [
                    'label'     =>  Yii::t('rbac', 'New role'),
                    'url'       =>  ['/rbac/role/create']
                ],
                [
                    'label'     =>  Yii::t('rbac', 'New permission'),
                    'url'       =>  ['/rbac/permission/create']
                ]
            ]
        ]
    ]
]) ?>
