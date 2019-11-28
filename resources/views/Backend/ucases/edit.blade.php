@php
    /**
     * The table ucases for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

<div class="ibox-title">
    <h5>Chỉnh sửa chức năng</h5>
    <div class="ibox-tools">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
            <i class="fa fa-wrench"></i>
            Modules
        </a>
        <ul class="dropdown-menu dropdown-menu-right dropdown-form" x-placement="bottom-start">
            <li><a href="#" class="hide">Ẩn controller</a></li>
            <li><a href="#" class="controller">Nhập controller</a></li>
            <li><a href="#" class="custom_link">Nhập custom link</a></li>
        </ul>
    </div>
</div>
<div class="ibox-content ibox-edit">
    <div class="sk-spinner sk-spinner-wave">
        <div class="sk-rect1"></div>
        <div class="sk-rect2"></div>
        <div class="sk-rect3"></div>
        <div class="sk-rect4"></div>
        <div class="sk-rect5"></div>
    </div>
    <form action="#" id="edit-post" method="post">
        <input type="hidden" name="id" value="">
        <input type="hidden" name="position" value="left">
        <input type="hidden" name="access" value="1">
        <input type="hidden" name="_slug" value="">

        <div class="client-detail">

            <div class="form-group field-position row">
                <label class="col-sm-3 col-form-label">
                    Vị trí
                </label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <div class="btn-group">
                            <button class="btn btn-white active" type="button" data-name="position" value="left">
                                Left
                            </button>
                            <button class="btn btn-white" type="button" data-name="position" value="top">
                                Top
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hr-line-dashed field-position"></div>

            <div class="form-group field-name row">
                <label class="col-sm-3 col-form-label">
                    Tên route
                </label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="text" name="slug" class="form-control required" placeholder="Tên route"
                               maxlength="100">
                        <span class="input-group-append">
                            <select name="status" class="btn btn-white dropdown-toggle">
                                <option value="1">Kích hoạt</option>
                                <option value="2">Kích hoạt ẩn</option>
                                <option value="0">Không kích hoạt</option>
                            </select>
                        </span>
                    </div>
                </div>
            </div>
            <div class="hr-line-dashed"></div>

            <div class="form-group field-title row">
                <label class="col-sm-3 col-form-label">
                    Tên hiển thị
                </label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="text" name="title" class="form-control required" placeholder="Tên hiển thị"
                               maxlength="100">
                    </div>
                </div>
            </div>
            <div class="hr-line-dashed"></div>

            <div class="form-group field-access row">
                <label class="col-sm-3 col-form-label">
                    Access
                </label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <div class="btn-group">
                            <button class="btn btn-white active" type="button" data-name="access" value="1">Backend
                            </button>
                            <button class="btn btn-white" type="button" data-name="access" value="2">Frontend</button>
                            <button class="btn btn-white" type="button" data-name="access" value="0">Public</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hr-line-dashed"></div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">
                    Descriptions
                </label>
                <div class="col-sm-9">
                    <div class="input-group">
                    <textarea type="text" name="route[description]" class="form-control" placeholder="Mô tả">
                    </textarea>
                    </div>
                </div>
            </div>
            <div class="hr-line-dashed"></div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">
                    Icon
                </label>
                <div class="col-sm-9">
                    <div class="input-group">
                        @include( name_blade('Fields.icon.icon'), [
                            'name'  =>  'route[icon]',
                            'value' =>  '',
                        ])
                    </div>
                </div>
            </div>
            <div class="hr-line-dashed"></div>

            <div class="form-group field-module row hidden">
                <label class="col-sm-3 col-form-label">
                    Module
                </label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="text" name="route[module]" class="form-control" placeholder="Module Name">
                    </div>
                </div>
            </div>
            <div class="hr-line-dashed field-module hidden"></div>

            <div class="form-group field-controller row hidden">
                <label class="col-sm-3 col-form-label">
                    Controller
                </label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="text" name="route[controller]" class="form-control" placeholder="Controller"
                               required="" aria-required="true">
                    </div>
                </div>
            </div>
            <div class="hr-line-dashed field-controller hidden"></div>

            <div class="form-group field-custom_link row hidden">
                <label class="col-sm-3 col-form-label">
                    Custom Link
                </label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="text" name="route[custom_link]" class="form-control" placeholder="Custom Link">
                        <span class="input-group-append">
                            <select name="route[method]" class="btn btn-white dropdown-toggle">
                                <option value="get">GET</option>
                                <option value="post">POST</option>
                                <option value="put">PUT</option>
                                <option value="patch">PATCH</option>
                                <option value="delete">DELETE</option>
                            </select>
                        </span>
                    </div>
                </div>
            </div>
            <div class="hr-line-dashed field-custom_link hidden"></div>

        </div>

        <div class="form-group submit row">
            <div class="col-sm-12">
                {{ button_set( 'delete', $permission, [ 'class'=>'btn btn-danger d-none' ] ) }}
                {{ button_set( 'edit', $permission, [ 'class'=>'btn btn-primary d-none' ] ) }}
                {{ button_set( 'add', $permission, [ 'text'=>'Thêm chức năng' ] ) }}
            </div>
        </div>
    </form>
</div>
