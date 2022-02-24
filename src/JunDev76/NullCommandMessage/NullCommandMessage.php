<?php

namespace JunDev76\NullCommandMessage;

use pocketmine\event\EventPriority;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\TextPacket;
use pocketmine\plugin\PluginBase;
use ReflectionException;

class NullCommandMessage extends PluginBase implements Listener{

    /**
     * @throws ReflectionException
     */
    public function onEnable() : void{
        $this->getServer()->getPluginManager()->registerEvent(DataPacketSendEvent::class, function(DataPacketSendEvent $ev){
            $pks = $ev->getPackets();
            foreach($pks as $pk){
                if(($pk instanceof TextPacket)){
                    if(str_contains(($message = $pk->message), '§c알 수 없는 명령어입니다: ')){
                        $message = str_replace('알 수 없는 명령어입니다: ', '', $message);
                        $command = explode('.', $message)[0];
                        $pk->type = TextPacket::TYPE_RAW;
                        $pk->message = '§c알 수 없는 명령어: ' . $command . '§r§c. 해당 명령어가 존재하는지 그리고 사용 권한이 있는지 확인해주세요.';
                        return;
                    }
                    if(str_contains(($message), '명령어를 사용할 수 있는 권한이 없습니다: ')){
                        $message = str_replace('명령어를 사용할 수 있는 권한이 없습니다: ', '', $message);
                        $pk->type = TextPacket::TYPE_RAW;
                        $pk->message = '§c알 수 없는 명령어: ' . $message . '§r§c. 해당 명령어가 존재하는지 그리고 사용 권한이 있는지 확인해주세요.';
                    }
                }
            }
        }, EventPriority::NORMAL, $this);
    }

}