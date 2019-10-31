@php
    /**
     * The edit ucases for our theme
     *
     * @package Ovic
     * @subpackage Framework
     *
     * @version 1.0
     */
@endphp

@push( 'styles.table' )
    <!-- Chosen -->
    <link href="{{ asset('css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">

    <style>
        div.chosen-container-multi .chosen-choices li.search-choice {
            margin: 5px 0 3px 5px;
        }

        .form-group.field-name {
            padding-top: 10px;
        }
    </style>
@endpush

@push( 'scripts.table' )
    <!-- Chosen -->
    <script src="{{ asset('js/plugins/chosen/chosen.jquery.js') }}"></script>

    <script>
        $( '.chosen-select' ).chosen( {
            width: "100%",
            no_results_text: "Oops, nothing found!",
            disable_search_threshold: 5
        } );
        /* Edit */
        $( document ).on( 'click', '#table-posts tbody > tr', function () {
            let row = $( this ),
                form = $( '#edit-post' ),
                icon = form.find( '.ovic-field-icon' ),
                role = OvicTable.row( this ).data();

            if ( !row.hasClass( 'active' ) ) {
                /* active */
                row.addClass( 'active' ).siblings().removeClass( 'active' );

                $.each( role, function ( index, value ) {
                    if ( $.isPlainObject( value ) ) {
                        $.each( value, function ( objIndex, objValue ) {
                            let name = '[name="' + index + '[' + objIndex + ']"]';

                            if ( form.find( name ).length ) {
                                if ( objIndex === 'icon' ) {
                                    icon.find( 'i' ).removeAttr( 'class' ).addClass( objValue );
                                    icon.find( 'input' ).val( objValue ).trigger( 'change' );
                                    icon.find( '.ovic-icon-preview' ).removeClass( 'd-none' );
                                    icon.find( '.ovic-icon-remove' ).removeClass( 'd-none' );
                                } else {
                                    form.find( name ).val( objValue ).trigger( 'change' );
                                }
                            }
                        } );
                    } else if ( form.find( '[name="' + index + '"]' ).length ) {
                        if ( index === 'parent_id' ) {
                            form.find( '[name="' + index + '"]' ).val( value ).trigger( 'chosen:updated' );
                        } else {
                            form.find( '[name="' + index + '"]' ).val( value ).trigger( 'change' );
                        }
                    }
                } );

                form.find( '.form-group .add-post' ).addClass( 'd-none' );
                form.find( '.form-group .update-post,.form-group .remove-post' ).removeClass( 'd-none' );

            } else {
                $( '.wrapper-content .btn.add-new' ).trigger( 'click' );
            }
        } );
        /* Add new */
        $( document ).on( 'click', '.wrapper-content .btn.add-new', function () {
            let form = $( '#edit-post' ),
                table = $( '#table-posts' );

            table.find( 'tbody > tr' ).removeClass( 'active' );
            form.trigger( 'reset' );
            form.find( '.ovic-icon-remove' ).trigger( 'click' );
            form.find( 'input[name="id"]' ).val( '' ).trigger( 'change' );
            form.find( 'select[name="parent_id"]' ).val( 0 ).trigger( 'chosen:updated' );
            form.find( '.form-group .add-post' ).removeClass( 'd-none' ).siblings().addClass( 'd-none' );

            return false;
        } );
        /* Status */
        $( document ).on( 'click', '#table-posts .status', function () {

            $( this ).update_status(
                "ucases",
                "Tắt chức năng thành công",
                "Kích hoạt thành công"
            );

            return false;
        } );
        /* Add post */
        $( document ).on( 'click', '.wrapper-content .btn.add-post', function () {
            let button = $( this ),
                form = $( '#edit-post' ),
                data = form.serializeObject();

            button.add_new( "ucases", data );

            return false;
        } );
        /* Update post */
        $( document ).on( 'click', '.wrapper-content .btn.update-post', function () {
            let button = $( this ),
                form = $( '#edit-post' ),
                data = form.serializeObject();

            button.update_post( "ucases", data, "#table-posts" );

            return false;
        } );
        /* Remove post */
        $( document ).on( 'click', '.wrapper-content .btn.remove-post', function () {
            let button = $( this ),
                form = $( '#edit-post' ),
                data = form.serializeObject();

            data.name = data.title;
            button.remove_post( "ucases", data );

            return false;
        } );
        /* Update select parent_id */
        $( document ).on( 'success_load_dataTable', function ( event, settings ) {
            let select = $( 'select[name="parent_id"]' ),
                option = '';

            if ( settings.data.length > 0 ) {
                option += '<option value="0">Không thuộc nhóm nào</option>';
                $.each( settings.data, function ( index, value ) {
                    option += '<option value="' + value.id + '">' + value.title + '</option>';
                } );
                select.empty(); //remove all child nodes
                select.append( option ).trigger( 'chosen:updated' );
            }
        } );
    </script>
@endpush

<form action="#" id="edit-post" method="post">
    <input type="hidden" name="id" value="">

    <div class="client-detail">

        <div class="form-group field-name row">
            <label class="col-sm-3 col-form-label">
                Tên router
            </label>
            <div class="col-sm-9">
                <div class="input-group">
                    <input type="text" name="slug" class="form-control" placeholder="Tên router"
                           required="" aria-required="true">
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

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">
                Tên hiển thị
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
                Thuộc nhóm
            </label>
            <div class="col-sm-9">
                <div class="input-group">
                    <select name="parent_id" class="btn btn-white dropdown-toggle chosen-select btn-block">
                        <option value="0">Không thuộc nhóm nào</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="hr-line-dashed"></div>

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">
                Quyền truy cập
            </label>
            <div class="col-sm-9">
                <div class="input-group">
                    <select name="access" class="btn btn-white dropdown-toggle btn-block text-left">
                        <option value="1">Backend</option>
                        <option value="2">Frontend</option>
                        <option value="0">Public</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="hr-line-dashed"></div>

        <div class="form-group field-module row">
            <label class="col-sm-3 col-form-label">
                Module
            </label>
            <div class="col-sm-9">
                <div class="input-group">
                    <input type="text" name="router[module]" class="form-control" placeholder="Module Name"
                           required="" aria-required="true" maxlength="100">
                </div>
            </div>
        </div>
        <div class="hr-line-dashed field-module"></div>

        <div class="form-group field-controller row">
            <label class="col-sm-3 col-form-label">
                Controller
            </label>
            <div class="col-sm-9">
                <div class="input-group">
                    <input type="text" name="router[controller]" class="form-control" placeholder="Controller"
                           required="" aria-required="true" maxlength="150">
                </div>
            </div>
        </div>
        <div class="hr-line-dashed field-controller"></div>

        <div class="form-group field-custom_link row">
            <label class="col-sm-3 col-form-label">
                Custom Link
            </label>
            <div class="col-sm-9">
                <div class="input-group">
                    <input type="text" name="router[custom_link]" class="form-control" placeholder="Custom Link"
                           required="" aria-required="true" maxlength="150">
                </div>
            </div>
        </div>
        <div class="hr-line-dashed field-custom_link"></div>

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">
                Descriptions
            </label>
            <div class="col-sm-9">
                <div class="input-group">
                    <textarea type="text" name="router[description]" class="form-control" placeholder="Mô tả">
                    </textarea>
                </div>
            </div>
        </div>
        <div class="hr-line-dashed"></div>

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">
                Vị trí
            </label>
            <div class="col-sm-9">
                <div class="input-group">
                    <select name="position" class="btn btn-white dropdown-toggle btn-block text-left">
                        <option value="top">Top</option>
                        <option value="left" selected>Left</option>
                        <option value="right">Right</option>
                        <option value="button">Bottom</option>
                    </select>
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
                    @include( ovic_blade('Fields.icon.icon'), [
                        'name'  =>  'router[icon]',
                        'value' =>  '',
                    ])
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
                Thêm chức năng
            </button>
        </div>
    </div>
</form>
