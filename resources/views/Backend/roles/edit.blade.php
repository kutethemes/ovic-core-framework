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

@push( 'scripts.users' )
    <script>
        /* Edit */
        $( document ).on( 'click', '#table-posts tbody > tr', function () {
            let row = $( this ),
                form = $( '#edit-post' ),
                role = OvicTable.row( this ).data();

            if ( !row.hasClass( 'active' ) ) {
                /* active */
                row.addClass( 'active' ).siblings().removeClass( 'active' );

                $.each( role, function ( index, value ) {
                    if ( form.find( '[name="' + index + '"]' ).length ) {
                        form.find( '[name="' + index + '"]' ).val( value ).trigger( 'change' );
                    }
                } );

                form.find( '.form-group .add-post' ).addClass( 'd-none' );
                form.find( '.form-group .update-post,.form-group .remove-post' ).removeClass( 'd-none' );

            } else {
                $( '.wrapper-content .btn.add-new' ).trigger( 'click' );
            }
        } );
        /* Status */
        $( document ).on( 'click', '#table-posts .status', function () {

            $( this ).update_status(
                "roles",
                "Tắt nhóm thành công",
                "Kích hoạt nhóm thành công"
            );

            return false;
        } );
        /* Add new */
        $( document ).on( 'click', '.wrapper-content .btn.add-new', function () {
            let form = $( '#edit-post' ),
                table = $( '#table-posts' );

            table.find( 'tbody > tr' ).removeClass( 'active' );
            form.trigger( 'reset' );
            form.find( 'input[name="id"]' ).val( '' ).trigger( 'change' );
            form.find( '.form-group .add-post' ).removeClass( 'd-none' ).siblings().addClass( 'd-none' );

            return false;
        } );
        /* Add post */
        $( document ).on( 'click', '.wrapper-content .btn.add-post', function () {
            let button = $( this ),
                form = $( '#edit-post' ),
                data = form.serializeObject();

            button.add_new( "roles", data );

            return false;
        } );
        /* Update post */
        $( document ).on( 'click', '.wrapper-content .btn.update-post', function () {
            let button = $( this ),
                form = $( '#edit-post' ),
                data = form.serializeObject();

            button.update_post( "roles", data, "#table-users" );

            return false;
        } );
        /* Remove post */
        $( document ).on( 'click', '.wrapper-content .btn.remove-post', function () {
            let button = $( this ),
                form = $( '#edit-post' ),
                data = form.serializeObject();

            button.remove_post( "roles", data );

            return false;
        } );
    </script>
@endpush

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
