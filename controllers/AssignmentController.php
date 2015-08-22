<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace wartron\yii2account\rbac\controllers;

use wartron\yii2account\rbac\models\Assignment;
use Yii;
use yii\web\Controller;
use wartron\yii2uuid\helpers\Uuid;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class AssignmentController extends Controller
{
    /**
     * Show form with auth items for user.
     *
     * @param hex $id
     */
    public function actionAssign($id)
    {
        $id = Uuid::str2uuid($id);
        $model = Yii::createObject([
            'class'   => Assignment::className(),
            'account_id' => $id,
        ]);

        if ($model->load(\Yii::$app->request->post()) && $model->updateAssignments()) {
        }

        return \wartron\yii2account\rbac\widgets\Assignments::widget([
            'model' => $model,
        ]);
        /*$model = Yii::createObject([
            'class'   => Assignment::className(),
            'account_id' => $id,
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->updateAssignments()) {

        }

        return $this->render('assign', [
            'model' => $model,
        ]);*/
    }
}