<?php

/*
 * This file is part of the Dektrium project
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace wartron\yii2account\rbac\widgets;

use wartron\yii2account\rbac\components\DbManager;
use wartron\yii2account\rbac\models\Assignment;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;

/**
 * This widget may be used in user update form and provides ability to assign
 * multiple auth items to the user.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Assignments extends Widget
{
    /** @var integer ID of the user to whom auth items will be assigned. */
    public $accountId;

    /** @var DbManager */
    protected $manager;

    /** @inheritdoc */
    public function init()
    {
        parent::init();
        $this->manager = Yii::$app->authManager;
        if ($this->accountId === null) {
            throw new InvalidConfigException('You should set ' . __CLASS__ . '::$accountId');
        }
    }

    /** @inheritdoc */
    public function run()
    {
        $model = Yii::createObject([
            'class'   => Assignment::className(),
            'account_id' => $this->accountId,
        ]);

        if ($model->load(\Yii::$app->request->post())) {
            $model->updateAssignments();
        }

        return $this->render('form', [
            'model' => $model,
        ]);
    }
}