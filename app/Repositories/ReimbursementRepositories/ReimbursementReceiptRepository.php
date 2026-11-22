<?php

namespace App\Repositories\ReimbursementRepositories;

use App\Models\Reimbursement\ReimbursementReceipt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Repositories\IRepository;

class ReimbursementReceiptRepository implements IRepository
{
    public function create(array $data)
    {
        $receipt = new ReimbursementReceipt($data);
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

        if ($receipt->image_url != "") {
            Storage::delete('public/' . $receipt->image_url);
        }
    }

    public function find($id, $columns = array('*'))
    {
        return $this->findBy('id', $id, $columns);
    }

    public function findBy($field, $value, $columns = array('*'))
    {
        return ReimbursementReceipt::where($field, '=', $value)->first($columns);
    }

    public function all($columns = array('*'))
    {
        return ReimbursementReceipt::all();
    }
}
