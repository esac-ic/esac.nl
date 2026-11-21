<?php

namespace App\Repositories\ReimbursementRepositories;

use App\Models\Reimbursement\ReimbursementItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Repositories\IRepository;

class ReimbursementItemRepository implements IRepository
{
    public function create(array $data)
    {
        $receipt = new ReimbursementItem($data);
        $receipt->save();

        return $receipt;
    }

    public function update($id, array $data)
    {
        $receipt = $this->find($id);
        $receipt->update($data);
        $receipt->save();

        return $receipt;
    }

    public function delete($id)
    {
        $receipt = $this->find($id);
        $receipt->delete();
    }

    public function find($id, $columns = array('*'))
    {
        return $this->findBy('id', $id, $columns);
    }

    public function findBy($field, $value, $columns = array('*'))
    {
        return ReimbursementItem::where($field, '=', $value)->get($columns);
    }

    public function all($columns = array('*'))
    {
        return ReimbursementItem::all();
    }
}
