@php
    /**
     * The edit role for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

<div class="ibox-title">
    <h5>Chỉnh sửa nhóm</h5>
</div>
<div class="ibox-content">
    <form action="#" id="edit-post" method="post">
        <input type="hidden" name="id" value="">

        <div class="client-detail">

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">
                    Name
                </label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="text" name="name" class="form-control required" placeholder="Tên riêng"
                               maxlength="100">
                        <span class="input-group-append">
                            <select name="status" class="btn btn-white dropdown-toggle">
                                <option value="1">Kích hoạt</option>
                                <option value="0">Không kích hoạt</option>
                            </select>
                        </span>
                    </div>
                </div>
            </div>
            <div class="hr-line-dashed"></div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">
                    Title
                </label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="text" name="title" class="form-control required" placeholder="Tên hiển thị"
                               maxlength="100">
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
                        <textarea type="text" name="description" class="form-control" placeholder="Mô tả">
                        </textarea>
                    </div>
                </div>
            </div>
            <div class="hr-line-dashed"></div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">
                    Ordering
                </label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="number" name="ordering" class="form-control" min="0" value="99"/>
                        <div class="alert alert-warning">
                            Số thứ tự ngoài việc sử dụng để sắp xếp khi hiển thị nó còn thể hiện phân cấp khi tạo tài khoản.
                        </div>
                    </div>
                </div>
            </div>
            <div class="hr-line-dashed"></div>

        </div>

        <div class="form-group submit row">
            <div class="col-sm-12">
                {{ button_set( 'delete', $permission, [ 'class'=>'btn-danger d-none' ] ) }}
                {{ button_set( 'edit', $permission, [ 'class'=>'btn-primary d-none' ] ) }}
                {{ button_set( 'add', $permission ) }}
            </div>
        </div>
    </form>
</div>
