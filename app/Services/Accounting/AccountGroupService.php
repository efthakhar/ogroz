<?php


namespace App\Services\Accounting;

use App\Models\Accounting\AccountGroup;
use Illuminate\Support\Facades\DB;

class AccountGroupService
{
    public static function getAllNestedChildrenIds($id)
    {
        $query = "WITH RECURSIVE nested_items AS (
            SELECT id, name, parent_account_group_id
            FROM account_groups
            WHERE id = ?

            UNION ALL

            SELECT i.id, i.name, i.parent_account_group_id
            FROM account_groups i
            INNER JOIN nested_items ni ON i.parent_account_group_id = ni.id
        )
        SELECT * FROM nested_items WHERE id != ?;";
        $result = DB::select($query, [$id, $id]);
        $ids = array_column($result, 'id');
        return $ids;
    }
}
