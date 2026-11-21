<?php

namespace App\Repositories\ReimbursementRepositories;

use App\Models\Reimbursement\Reimbursement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Repositories\IRepository;

class ReimbursementRepository implements IRepository
{
    public function create(array $data)
    {
        $reimbursement = new Reimbursement($data);

        $reimbursement->title = $data['title'];
        $reimbursement->text = $data['text'];
        $reimbursement->type = $data['type'];
        $reimbursement->status = $data['status'];
        $reimbursement->reimbursement_id = $data['reimbursement_id'];

        $reimbursement->createdOn = Carbon::now();
        $reimbursement->createdBy = Auth::user()->id;

        return $reimbursement;
    }

    public function update($id, array $data)
    {
        $reimbursement = $this->find($id);
        $reimbursement->update($data);

        return $reimbursement;
    }

    public function delete($id)
    {
        $reimbursement = $this->find($id);
        $reimbursement->delete();
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
}
