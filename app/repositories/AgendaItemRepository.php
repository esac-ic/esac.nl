<?php
/**
 * Created by PhpStorm.
 * User: Niekh
 * Date: 6-4-2017
 * Time: 21:48
 */

namespace App\repositories;


use App\AgendaItem;
use Carbon\Carbon;
use Storage;

class AgendaItemRepository implements IRepository
{
    private $_textRepository;

    /**
     * AgendaItemRepository constructor.
     * @param $_textRepository
     */
    public function __construct(TextRepository $_textRepository)
    {
        $this->_textRepository = $_textRepository;
    }

    public function create(array $data)
    {
        $title = $this->_textRepository->create(['NL_text' => $data['NL_title'], 'EN_text' => $data['EN_title']]);
        $text = $this->_textRepository->create($data);
        $shortDescription = $this->_textRepository->create(['NL_text' => $data['NL_shortDescription'], 'EN_text' => $data['EN_shortDescription']]);

        $agendaItem = new AgendaItem($data);
        $starttime = new \DateTime($data['startDate']);
        $endtime = new \DateTime($data['endDate']);
        $subscription_endDate = new \DateTime($data['subscription_endDate']);
        $agendaItem->startDate = Carbon::createFromFormat('d-m-Y H:i',  $starttime->format('d-m-Y') . ' ' . $starttime->format('H:i'));
        $agendaItem->endDate = Carbon::createFromFormat('d-m-Y H:i',  $endtime->format('d-m-Y') . ' ' . $endtime->format('H:i'));
        if($data['applicationForm'] != 0){
            $agendaItem->subscription_endDate = Carbon::createFromFormat('d-m-Y H:i',  $subscription_endDate->format('d-m-Y') . ' ' . $subscription_endDate->format('H:i'));
            $agendaItem->application_form_id = $data['applicationForm'];
        } else {
            $agendaItem->subscription_endDate = null;
            $agendaItem->application_form_id = null;
        }

        $agendaItem->title = $title->id;
        $agendaItem->text = $text->id;
        $agendaItem->shortDescription = $shortDescription->id;
        $agendaItem->image_url = "";
        $agendaItem->createdBy = \Auth::user()->id;
        $agendaItem->climbing_activity = array_key_exists('climbing_activity', $data);
        $agendaItem->save();
        return $agendaItem;
    }

    public function update($id, array $data)
    {
        $agendaItem = $this->find($id);

        //update text
        $this->_textRepository->update($agendaItem->title,['NL_text' => $data['NL_title'], 'EN_text' => $data['EN_title']]);
        $this->_textRepository->update($agendaItem->text,$data);
        $this->_textRepository->update($agendaItem->shortDescription,['NL_text' => $data['NL_shortDescription'], 'EN_text' => $data['EN_shortDescription']]);

        $starttime = new \DateTime($data['startDate']);
        $endtime = new \DateTime($data['endDate']);
        $subscription_endDate = new \DateTime($data['subscription_endDate']);
        $agendaItem->category = $data['category'];
        $agendaItem->startDate = Carbon::createFromFormat('d-m-Y H:i',  $starttime->format('d-m-Y') . ' ' . $starttime->format('H:i'));
        $agendaItem->endDate = Carbon::createFromFormat('d-m-Y H:i',  $endtime->format('d-m-Y') . ' ' . $endtime->format('H:i'));
        if($data['applicationForm'] != 0){
            $agendaItem->subscription_endDate = Carbon::createFromFormat('d-m-Y H:i',  $subscription_endDate->format('d-m-Y') . ' ' . $subscription_endDate->format('H:i'));
            $agendaItem->application_form_id = $data['applicationForm'];
        } else {
            $agendaItem->subscription_endDate = null;
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

        $this->_textRepository->delete($agendaItem->title);
        $this->_textRepository->delete($agendaItem->text);

        if( $agendaItem->image_url != ""){
            \Storage::delete('public/'. $agendaItem->image_url);
        }
    }

    public function find($id, $columns = array('*'))
    {
        return $this->findBy('id',$id,$columns);
    }

    public function findBy($field, $value, $columns = array('*'))
    {
        return AgendaItem::query()->where($field,'=',$value)->first($columns);
    }

    public function all($columns = array('*'))
    {
        return AgendaItem::all($columns);
    }

    /**
     * @param AgendaItem $agendaItem
     * @return AgendaItem
     */
    public function copy(AgendaItem $agendaItem): AgendaItem
    {
        $newAgendaItem = $agendaItem->replicate();
        $newAgendaItem->createdBy = \Auth::user()->id;

        $title = $agendaItem->agendaItemTitle->replicate();
        $title->save();
        $text = $agendaItem->agendaItemText->replicate();
        $text->save();
        $shortDescription = $agendaItem->agendaItemShortDescription->replicate();
        $shortDescription->save();

        $newAgendaItem->text = $text->id;
        $newAgendaItem->title = $title->id;
        $newAgendaItem->shortDescription = $shortDescription->id;
        $newAgendaItem->save();

        if ($agendaItem->image_url !== null && $agendaItem->image_url  !== "") {
            $oldPath = $agendaItem->image_url;
            $newPath = str_replace($agendaItem->id,$newAgendaItem->id, $oldPath);
            Storage::disk('public')->copy($oldPath, $newPath);
            $newAgendaItem->image_url = $newPath;
            $newAgendaItem->save();
        }


        return $newAgendaItem;
    }

    /**
     * Returns the first X amount of items from now
     * @param $amount
     */
    public function getFirstXItems($limit){
        return AgendaItem::query()
            ->with('agendaItemShortDescription','agendaItemTitle','getApplicationFormResponses')
            ->whereDate('startDate','>=', Carbon::now())
            ->orderBy('startDate', 'ASC')
            ->take($limit)
            ->get();
    }
}