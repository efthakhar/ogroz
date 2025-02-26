<?php


namespace App\Services\Accounting;

use App\Models\Accounting\AccountGroup;

class AccountGroupService
{
    public function getTree()
    {
        return $this->buildTree(AccountGroup::orderBy('type')->get());
    }

    private function buildTree($items, $parentId = null)
    {
        return $items
            ->where('parent_account_group_id', $parentId)
            ->map(function ($item) use ($items) {
                $item->childrens = $this->buildTree($items, $item->id);
                return $item;
            })
            ->values();
    }
}
