<?php

namespace Ovic\Framework;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Maatwebsite\Excel\Validators\Failure;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class UsersImport implements ToModel, WithStartRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    public $role_ids = [];
    public $donvi_id = [];

    public function __construct( $role_ids = [], $donvi_id = '' )
    {
        $this->role_ids = !empty($role_ids) ? (array) $role_ids : 0;
        $this->donvi_id = !empty($donvi_id) ? $donvi_id : 0;
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 5;
    }

    public function rules(): array
    {
        return [
            '2' => Rule::unique('users', 'email'),
            '0' => Rule::unique('users', 'canhan_id'),
        ];
    }

    public function model( array $row )
    {
        return new Users([
            'name'      => trim($row[1]),
            'email'     => trim($row[2]),
            'canhan_id' => trim($row[0]),
            'password'  => Hash::make(trim($row[3])),
            'role_ids'  => maybe_serialize($this->role_ids),
            'donvi_id'  => $this->donvi_id,
            'donvi_ids' => 0,
            'status'    => 1,
        ]);
    }
}
