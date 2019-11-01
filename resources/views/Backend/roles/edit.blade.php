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
    <div class="ibox-tools">
        <a class="collapse-link">
            <i class="fa fa-chevron-up"></i>
        </a>
    </div>
</div>
<div class="ibox-content">
    <form action="#" id="edit-post" method="post">
        <input type="hidden" name="id" value="">

        <div class="client-detail">
            <div class="full-height-scroll">

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">
                        Name
                    </label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <input type="text" name="name" class="form-control" placeholder="Tên riêng"
                                   required="" aria-required="true" maxlength="150">
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
                            <input type="text" name="title" class="form-control" placeholder="Tên hiển thị"
                                   required="" aria-required="true" maxlength="150">
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
                        </div>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>

            </div>
        </div>

        <div class="form-group submit row">
            <div class="col-sm-12">
                <button type="button" class="btn btn-danger remove-post d-none">
                    <i class="fa fa-trash-o"></i>
                    Xóa
                </button>
                <button class="btn btn-primary update-post d-none" type="button">
                    <i class="fa fa-save"></i>
                    Save change
                </button>
                <button class="btn btn-primary add-post" type="button">
                    <i class="fa fa-upload"></i>
                    Add role
                </button>
            </div>
        </div>
    </form>
</div>
