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
                'admin-rbac',
                2,
                'Can Manage RBAC',
            ],
            [
                'backend-accounts',
                2,
                'Can Manage Accounts',
            ],
            [
                'backend-accounts-confirm',
                2,
                'Can Confirm Accounts',
            ],
            [
                'backend-accounts-block',
                2,
                'Can Block Accounts',
            ],
            [
                'backend-accounts-delete',
                2,
                'Can Delete Accounts',
            ],
            [
                'backend-accounts-rbac',
                2,
                'Can Delete Accounts',
            ],
            [
                'backend-accounts-billing',
                2,
                'Can Manage Accounts Billing',
            ],
        ]);


        $columns = ['parent', 'child'];
        $this->batchInsert('{{%auth_item_child}}', $columns, [
            [
                'admin',
                'admin-rbac',
            ],
            [
                'admin',
                'backend',
            ],
            [
                'admin',
                'backend-accounts-delete',
            ],
            [
                'admin',
                'backend-accounts-rbac',
            ],
            [
                'admin',
                'backend-accounts-billing',
            ],
            [
                'backend',
                'backend-accounts',
            ],
            [
                'backend',
                'backend-accounts-confirm',
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
