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
 * @var $model dektrium\rbac\models\Role
 * @var $this  yii\web\View
 */

$this->title = Yii::t('rbac', 'View permission');
$this->params['breadcrumbs'][] = $this->title;

$this->beginContent('@wartron/yii2account/rbac/views/layout.php');


print_r($model);


$this->endContent();