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
    <h5>Danh sách nhóm người dùng</h5>
    <div class="ibox-tools">
        <a class="collapse-link">
            <i class="fa fa-chevron-up"></i>
        </a>
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
        <div class="client-detail">

            @if( !empty( $roles ) )
                <ul class="list-group elements-list">
                    @foreach( $roles as $role )
                        @php
                            $ucase_ids  = json_decode($role['ucase_ids'], true);
                            $count      = is_array($ucase_ids) ? count($ucase_ids) : 0;
                        @endphp
                        <li class="list-group-item">
                            <a href="#" class="nav-link" id="role-{{ $role['id'] }}" data-id="{{ $role['id'] }}"
                               data-ucase={{ $role['ucase_ids'] }}>
                                <small class="float-right text-muted">{{ $role['created_at'] }}</small>
                                <strong>{{ $role['title'] }}</strong>
                                <div class="small m-t-xs">
                                    <p class="m-b-xs">
                                        {{ $role['description'] }}
                                        <br>
                                    </p>
                                    <p class="m-b-none">
                                        <span class="label float-right label-primary">{{ $count }}</span>
                                    </p>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif

        </div>
    </form>
</div>
