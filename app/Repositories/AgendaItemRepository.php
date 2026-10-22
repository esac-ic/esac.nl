<?php

namespace App\Repositories;

use App\Models\AgendaItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AgendaItemRepository implements IRepository
{
    public function create(array $data)
    {
        $agendaItem = new AgendaItem($data);
        $starttime = new \DateTime($data['start_date']);
        $endtime = new \DateTime($data['end_date']);
        $subscription_endDate = new \DateTime($data['subscription_end_date']);
        $agendaItem->start_date = Carbon::createFromFormat('d-m-Y H:i', $starttime->format('d-m-Y') . ' ' . $starttime->format('H:i'));
        $agendaItem->end_date = Carbon::createFromFormat('d-m-Y H:i', $endtime->format('d-m-Y') . ' ' . $endtime->format('H:i'));

        if ($data['applicationForm'] != 0) {
            $agendaItem->subscription_end_date = Carbon::createFromFormat('d-m-Y H:i', $subscription_endDate->format('d-m-Y') . ' ' . $subscription_endDate->format('H:i'));
            $agendaItem->application_form_id = $data['applicationForm'];
        } else {
            $agendaItem->subscription_end_date = null;
            $agendaItem->application_form_id = null;
        }

        $agendaItem->image_url = "";
        $agendaItem->created_by = Auth::user()->id;
        $agendaItem->climbing_activity = array_key_exists('climbing_activity', $data);
        $agendaItem->save();

        return $agendaItem;
    }

    public function update($id, array $data)
    {
        $agendaItem = $this->find($id);
        $agendaItem->title = $data['title'];
        $agendaItem->text = $data['text'];
        $agendaItem->short_description = $data['short_description'];
        $agendaItem->category = $data['category'];
        $starttime = new \DateTime($data['start_date']);
        $endtime = new \DateTime($data['end_date']);
        $subscription_endDate = new \DateTime($data['subscription_end_date']);
        $agendaItem->start_date = Carbon::createFromFormat('d-m-Y H:i', $starttime->format('d-m-Y') . ' ' . $starttime->format('H:i'));
        $agendaItem->end_date = Carbon::createFromFormat('d-m-Y H:i', $endtime->format('d-m-Y') . ' ' . $endtime->format('H:i'));

        if ($data['applicationForm'] != 0) {
            $agendaItem->subscription_end_date = Carbon::createFromFormat('d-m-Y H:i', $subscription_endDate->format('d-m-Y') . ' ' . $subscription_endDate->format('H:i'));
            $agendaItem->application_form_id = $data['applicationForm'];
        } else {
            $agendaItem->subscription_end_date = null;
            $agendaItem->application_form_id = null;
        }

        $agendaItem->climbing_activity = array_key_exists('climbing_activity', $data);
        $agendaItem->save();

        return $agendaItem;
    }

    public function delete($id)
    {
        $agendaItem = $this->find($id);
        $agendaItem->delete();

        if ($agendaItem->image_url != "") {
            Storage::disk('public')->delete($agendaItem->image_url);
        }
    }

    public function find($id, $columns = array('*'))
    {
        return $this->findBy('id', $id, $columns);
    }

    public function findBy($field, $value, $columns = array('*'))
    {
        return AgendaItem::query()->where($field, '=', $value)->first($columns);
    }

    public function all($columns = array('*'))
    {
        return AgendaItem::all($columns);
    }

    public function copy(AgendaItem $agendaItem): AgendaItem
    {
        $newAgendaItem = $agendaItem->replicate();
        $newAgendaItem->created_by = Auth::user()->id;
        $newAgendaItem->save();

        if ($agendaItem->image_url !== null && $agendaItem->image_url !== "") {
            $oldPath = $agendaItem->image_url;
            $newPath = str_replace($agendaItem->id, $newAgendaItem->id, $oldPath);
            Storage::disk('public')->copy($oldPath, $newPath);
            $newAgendaItem->image_url = $newPath;
            $newAgendaItem->save();
        }

        return $newAgendaItem;
    }

    public function getFirstXItems($limit)
    {
        return AgendaItem::query()
            ->with('getApplicationFormResponses')
            ->whereDate('start_date', '>=', Carbon::now())
            ->orderBy('start_date', 'ASC')
            ->take($limit)
            ->get();
    }
}
