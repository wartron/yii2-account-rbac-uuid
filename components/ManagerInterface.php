<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace wartron\yii2account\rbac\components;

use yii\rbac\ManagerInterface as BaseManagerInterface;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
interface ManagerInterface extends BaseManagerInterface
{
    /**
     * @param  integer|null $type
     * @param  array        $excludeItems
     * @return mixed
     */
    public function getItems($type = null, $excludeItems = []);

    /**
     * @param  binary $accountId
     * @return mixed
     */
    public function getItemsByUser($accountId);

    /**
     * @param  string $name
     * @return mixed
     */
    public function getItem($name);
}