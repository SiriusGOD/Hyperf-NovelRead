<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
class PermissionSeed implements BaseInterface
{
    public function up(): void
    {
        $permissions = [
            # 角色
            'roles' => [
                'role-index',
                'role-create',
                'role-edit',
                'role-delete',
                'role-store',
            ],
            # 後台管理員
            'manager' => [
                'manager-index',
                'manager-create',
                'manager-edit',
                'manager-delete',
                'manager-store',
                'manager-googleAuth'
            ],
            # 用戶對應多站管理
            'usersite' => [
                'usersite-index',
                'usersite-create',
                'usersite-edit',
                'usersite-delete',
                'usersite-store',
            ],
            # 多站管理
            'site' => [
                'site-index',
                'site-create',
                'site-edit',
                'site-delete',
                'site-store',
            ],
            # 小說管理
            'book' => [
                'book-index',
                'book-create',
                'book-edit',
                'book-delete',
                'book-store',
            ],
            # 小說推薦管理
            'bookrecommend' => [
                'bookrecommend-index',
                'bookrecommend-create',
                'bookrecommend-edit',
                'bookrecommend-delete',
                'bookrecommend-store',
            ],
            # 書籍管理
            'bookcount' => [
                'bookcount-index',
                'bookcount-create',
                'bookcount-edit',
                'bookcount-delete',
                'bookcount-store',
            ],
        ];

        foreach ($permissions as $main => $permission) {
            foreach ($permission as $name) {
                $model = new \App\Model\Permission();
                $model->main = $main;
                $model->name = $name;
                $model->save();
            }
        }
    }

    public function down(): void
    {
        \App\Model\Permission::truncate();
    }

    public function base(): bool
    {
        return true;
    }
}
