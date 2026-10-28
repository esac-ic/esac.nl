<?php
/**
 * Created by PhpStorm.
 * User: Niekh
 * Date: 16-2-2017
 * Time: 21:31
 */

namespace App\Repositories;

interface IRepository
{
    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function find($id, $columns = ['*']);

    public function findBy($field, $value, $columns = ['*']);

    public function all($columns = ['*']);
}
