<table>
    <thead>
    <th height="25"></th>
    </thead>
</table>
<table>
    <thead>
    <th colspan="5" height="25"><b>DANH SÁCH ĐƠN VỊ</b></th>
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
        <th width="12" height="25"><b>ID đơn vị</b></th>
        <th width="25" height="25"><b>Tên đơn vị</b></th>
        <th width="40" height="25"><b>Email</b></th>
        <th width="25" height="25"><b>Mật khẩu</b></th>
        <th width="25" height="25"><b>Ghi chú</b></th>
    </tr>
    </thead>
    <tbody>
    @foreach( $users as $user )
        <tr>
            <td height="25">{{ $user['id'] }}</td>
            <td height="25">{{ $user['name'] }}</td>
            <td height="25">{{ $user['email'] }}</td>
            <td height="25"></td>
            <td height="25"></td>
        </tr>
    @endforeach
    </tbody>
</table>
