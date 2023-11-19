<?php

namespace Interaction\Web\Controller;

use Admin\App;
use Service\Data;
use Service\SlotFactory;

class Slot extends AuthControllerProto
{
    private $projectId;
    
    /**
     * @var Data
     */
    private $slots;
    
    public function before()
    {
        parent::before();
        $this->projectId = $this->p('pId');
        $this->slots = Data::scope(App::DATA_SLOTS);
    }

    public function isEnabled(): bool
    {
        return env('ENABLE_DEPLOY', false);
    }

    public function index() 
    {
        $this->setTitle('Сервера и слоты');

        $this->response(['slots' => $this->slots->getAll(),]);
    }
    
    public function create() 
    {
        $this->setTitle("Добавление слота");
        
        if ($this->projectId) {
            $this->setSubTitle("Для проекта #" . $this->projectId);
            $this->setSubTitle('<a href="/web/project/show/' . $this->projectId . '">Для проекта #' . $this->projectId.'</a>');
        }
        
    
        $slotId = $this->p('id');
        $slot = SlotFactory::getSlot($slotId);
        
        
        $slotData  = $slot->getData();
        if ($this->projectId) {
            $slotData  = [
                'projectId' => $this->projectId,
            ] + $slotData;
        }
        
        
        if ($this->app->getRequest()->isPost()) {
            $slotId = $slotId ?: crc32(microtime(1));
            
            $slotData = [
                'name' => $this->p('name'),
                'host' => $this->p('host'),
                'path' => $this->p('path'),
                'type' => $this->p('type'),
                'id' => $slotId,
            ] + $slotData;
    
            $this->slots->insertOrUpdate($slotId, $slotData)->write();
        }
        
        $this->response([
            'projectId' => $this->projectId,
            'slotData' => $slotData,
            'slotId' => $slotId,
        ]);
    }
}
