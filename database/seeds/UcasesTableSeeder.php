<?php
use Illuminate\Database\Seeder;

class UcasesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ucases = [
            [
                'slug'      => 'users',
                'title'     => 'Quản lý người dùng',
                'parent_id' => 0,
                'route'     => maybe_serialize([
                    'icon'        => 'fa fa-users',
                    'module'      => '',
                    'controller'  => 'Ovic\Framework\UsersController',
                    'custom_link' => '',
                    'description' => '',
                ]),
                'ordering'  => 1,
                'position'  => 'left',
                'access'    => 1,
                'status'    => 1,
            ],
            [
                'slug'      => 'roles',
                'title'     => 'Nhóm người dùng',
                'parent_id' => 0,
                'route'     => maybe_serialize([
                    'icon'        => 'fa fa-user-plus',
                    'module'      => '',
                    'controller'  => 'Ovic\Framework\RolesController',
                    'custom_link' => '',
                    'description' => '',
                ]),
                'ordering'  => 2,
                'position'  => 'left',
                'access'    => 1,
                'status'    => 1,
            ],
            [
                'slug'      => 'ucases',
                'title'     => 'Quản lý chức năng',
                'parent_id' => 0,
                'route'     => maybe_serialize([
                    'icon'        => 'fa fa-codepen',
                    'module'      => '',
                    'controller'  => 'Ovic\Framework\UcasesController',
                    'custom_link' => '',
                    'description' => '',
                ]),
                'ordering'  => 3,
                'position'  => 'left',
                'access'    => 1,
                'status'    => 1,
            ],
            [
                'slug'      => 'permission',
                'title'     => 'Phân quyền chức năng',
                'parent_id' => 0,
                'route'     => maybe_serialize([
                    'icon'        => 'fa fa-key',
                    'module'      => '',
                    'controller'  => 'Ovic\Framework\PermissionController',
                    'custom_link' => '',
                    'description' => '',
                ]),
                'ordering'  => 4,
                'position'  => 'left',
                'access'    => 1,
                'status'    => 1,
            ],
            [
                'slug'      => 'post',
                'title'     => 'Post',
                'parent_id' => 0,
                'route'     => maybe_serialize([
                    'icon'        => '',
                    'module'      => '',
                    'controller'  => 'Ovic\Framework\PostsController',
                    'custom_link' => '',
                    'description' => '',
                ]),
                'ordering'  => 5,
                'position'  => 'left',
                'access'    => 1,
                'status'    => 2,
            ],
            [
                'slug'      => 'upload',
                'title'     => 'Quản lý dữ liệu',
                'parent_id' => 0,
                'route'     => maybe_serialize([
                    'icon'        => 'fa fa-folder-open',
                    'module'      => '',
                    'controller'  => 'Ovic\Framework\UploadFileController',
                    'custom_link' => '',
                    'description' => '',
                ]),
                'ordering'  => 5,
                'position'  => 'left',
                'access'    => 1,
                'status'    => 1,
            ],
        ];
    }
}
