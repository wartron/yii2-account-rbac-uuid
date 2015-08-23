<?php

use yii\db\Migration;

class m150823_001311_create_default_roles extends Migration
{

    public function up()
    {
        $columns = ['name', 'type', 'description'];
        $this->batchInsert('{{%auth_item}}', $columns, [
            [
                'admin',
                1,
                'Role Admin',
            ],
            [
                'backend',
                1,
                'Role Backend',
            ],
            [
                'user',
                1,
                'Role User',
            ],
            [
                'backend-accounts',
                2,
                'Can Manage Accounts',
            ],
            [
                'backend-accounts-block',
                2,
                'Can Block Accounts',
            ],
            [
                'backend-accounts-delete',
                2,
                'Can Block Accounts',
            ],
            [
                'backend-rbac',
                2,
                'Can Manage RBAC',
            ],
        ]);


        $columns = ['parent', 'child'];
        $this->batchInsert('{{%auth_item_child}}', $columns, [
            [
                'admin',
                'backend',
            ],
            [
                'admin',
                'backend-rbac',
            ],
            [
                'admin',
                'backend-accounts-delete',
            ],
            [
                'backend',
                'backend-accounts',
            ],
            [
                'backend',
                'backend-accounts-block',
            ],
        ]);
    }

    public function down()
    {

    }
}
