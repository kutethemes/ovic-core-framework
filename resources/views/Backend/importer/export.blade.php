<table>
    <thead>
    <th height="25"></th>
    </thead>
</table>
<table>
    <thead>
    <th colspan="5" height="25"><b>DANH SÁCH CÁC CÁ NHÂN</b></th>
    </thead>
</table>
<table>
    <thead>
    <th height="25"></th>
    </thead>
</table>
<table class="table">
    <thead>
    <tr>
        <th width="10" height="25"><b>ID</b></th>
        <th width="25" height="25"><b>Họ và tên</b></th>
        <th width="40" height="25"><b>Email</b></th>
        <th width="25" height="25"><b>Mật khẩu</b></th>
        <th width="30" height="25"><b>Ghi chú</b></th>
    </tr>
    </thead>
    <tbody>
    @foreach( $users as $user )
        <tr>
            <td height="25">{{ $user['id'] }}</td>
            <td height="25">{{ $user['hodem'] .' '. $user['ten'] }}</td>
            <td height="25"></td>
            <td height="25"></td>
            <td height="25"></td>
        </tr>
    @endforeach
    </tbody>
</table>
