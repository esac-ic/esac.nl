<?php

namespace App\Repositories;

use App\Models\Reimbursement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReimbursementRepository implements IRepository
{
    public function create(array $data)
    {
        $reimbursement = new Reimbursement($data);

        return $reimbursement;
    }

    public function update($id, array $data)
    {
        $reimbursement = $this->find($id);

        return $reimbursement;
    }

    public function delete($id)
    {
        $reimbursement = $this->find($id);
        $reimbursement->delete();

        # TODO: Delete images
    }

    public function find($id, $columns = array('*'))
    {
        return $this->findBy('id', $id, $columns);
    }

    public function findBy($field, $value, $columns = array('*'))
    {
        return Reimbursement::query()->where($field, '=', $value)->first($columns);
    }

    public function all($columns = array('*'))
    {
        return Reimbursement::all($columns);
    }

    public function copy(Reimbursement $reimbursement): Reimbursement
    {
        $newReimbursement = $reimbursement->replicate();
 
        return $newReimbursement;
    }
}
