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

use yii\db\Query;
use yii\rbac\Assignment;
use yii\rbac\DbManager as BaseDbManager;
use wartron\yii2uuid\helpers\Uuid;

/**
 * This Auth manager changes visibility and signature of some methods from \yii\rbac\DbManager.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class DbManager extends BaseDbManager implements ManagerInterface
{

    /**
     * @inheritdoc
     */
    public function getRolesByUser($accountId)
    {
        if (empty($accountId)) {
            return [];
        }
        $query = (new Query)->select('b.*')
            ->from(['a' => $this->assignmentTable, 'b' => $this->itemTable])
            ->where('{{a}}.[[item_name]]={{b}}.[[name]]')
            ->andWhere(['a.account_id' => $accountId])
            ->andWhere(['b.type' => Item::TYPE_ROLE]);
        $roles = [];
        foreach ($query->all($this->db) as $row) {
            $roles[$row['name']] = $this->populateItem($row);
        }
        return $roles;
    }


    /**
     * @inheritdoc
     */
    public function getPermissionsByUser($accountId)
    {
        if (empty($accountId)) {
            return [];
        }
        $query = (new Query)->select('item_name')
            ->from($this->assignmentTable)
            ->where(['account_id' => $accountId]);
        $childrenList = $this->getChildrenList();
        $result = [];
        foreach ($query->column($this->db) as $roleName) {
            $this->getChildrenRecursive($roleName, $childrenList, $result);
        }
        if (empty($result)) {
            return [];
        }
        $query = (new Query)->from($this->itemTable)->where([
            'type' => Item::TYPE_PERMISSION,
            'name' => array_keys($result),
        ]);
        $permissions = [];
        foreach ($query->all($this->db) as $row) {
            $permissions[$row['name']] = $this->populateItem($row);
        }
        return $permissions;
    }


 /**
     * @inheritdoc
     */
    public function getAssignment($roleName, $accountId)
    {
        if (empty($accountId)) {
            return null;
        }
        $row = (new Query)->from($this->assignmentTable)
            ->where(['account_id' => $accountId, 'item_name' => $roleName])
            ->one($this->db);
        if ($row === false) {
            return null;
        }
        return new Assignment([
            'userId' => $row['account_id'],
            'roleName' => $row['item_name'],
            'createdAt' => $row['created_at'],
        ]);
    }
    /**
     * @inheritdoc
     */
    public function getAssignments($accountId)
    {
        if (empty($accountId)) {
            return [];
        }
        $query = (new Query)
            ->from($this->assignmentTable)
            ->where(['account_id' => $accountId]);
        $assignments = [];
        foreach ($query->all($this->db) as $row) {
            $assignments[$row['item_name']] = new Assignment([
                'userId' => $row['account_id'],
                'roleName' => $row['item_name'],
                'createdAt' => $row['created_at'],
            ]);
        }
        return $assignments;
    }


    /**
     * @inheritdoc
     */
    public function assign($role, $accountId)
    {
        $assignment = new Assignment([
            'userId' => $accountId,
            'roleName' => $role->name,
            'createdAt' => time(),
        ]);
        $this->db->createCommand()
            ->insert($this->assignmentTable, [
                'account_id'    =>  $assignment->userId,
                'item_name'     =>  $assignment->roleName,
                'created_at'    =>  $assignment->createdAt,
            ])->execute();
        return $assignment;
    }

    /**
     * @inheritdoc
     */
    public function revoke($role, $accountId)
    {
        if (empty($accountId)) {
            return false;
        }
        return $this->db->createCommand()
            ->delete($this->assignmentTable, ['account_id' => $accountId, 'item_name' => $role->name])
            ->execute() > 0;
    }
    /**
     * @inheritdoc
     */
    public function revokeAll($accountId)
    {
        if (empty($accountId)) {
            return false;
        }
        return $this->db->createCommand()
            ->delete($this->assignmentTable, ['account_id' => $accountId])
            ->execute() > 0;
    }

    /**
     * @param  int|null $type         If null will return all auth items.
     * @param  array    $excludeItems Items that should be excluded from result array.
     * @return array
     */
    public function getItems($type = null, $excludeItems = [])
    {
        $query = (new Query())
            ->from($this->itemTable);

        if ($type !== null) {
            $query->where(['type' => $type]);
        } else {
            $query->orderBy('type');
        }

        foreach ($excludeItems as $name) {
            $query->andWhere('name != :item', ['item' => $name]);
        }

        $items = [];

        foreach ($query->all($this->db) as $row) {
            $items[$row['name']] = $this->populateItem($row);
        }

        return $items;
    }

    /**
     * Returns both roles and permissions assigned to user.
     *
     * @param  binary $accountId
     * @return array
     */
    public function getItemsByUser($accountId)
    {
        if (empty($accountId)) {
            return [];
        }

        $query = (new Query)->select('b.*')
            ->from(['a' => $this->assignmentTable, 'b' => $this->itemTable])
            ->where('{{a}}.[[item_name]]={{b}}.[[name]]')
            ->andWhere(['a.account_id' => $accountId]);

        $roles = [];
        foreach ($query->all($this->db) as $row) {
            $roles[$row['name']] = $this->populateItem($row);
        }
        return $roles;
    }

    /** @inheritdoc */
    public function getItem($name)
    {
        return parent::getItem($name);
    }
}