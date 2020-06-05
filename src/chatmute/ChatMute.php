<?php
declare(strict_types=1);

namespace matymare\chatmute;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use function str_repeat;

class ChatMute extends PluginBase implements Listener{

    public function onEnable() : void{
        $this->getServer()->getLogger()->info(TextFormat::GOLD."§8[§eChatMute§8] §cPlugin made by matymare");
        $this->getServer()->getLogger()->info(TextFormat::RED."§8[§eChatMute§8] §cDiscord: Roospy#1666");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
        $action = $args[0] ?? "";
        if($action === "on" || $action === "off"){
            $config = $this->getConfig();
            $config->set("global-mute", $action === "on");
            if($action === "on" && $config->get("clear-chat") === true){
                $this->getServer()->broadcastMessage(str_repeat("\n", 100));
            }
            $this->getServer()->broadcastMessage($config->get("turned-" . $action, "Chat muted " . $action . "."));

            return true;
        }
        $sender->sendMessage(TextFormat::RED . "Use '/chc <on|off>'");

        return false;
    }

    public function onPlayerChat(PlayerChatEvent $event){
        $config = $this->getConfig();
        if($config->get("global-mute") === true && !$event->getPlayer()->hasPermission("chat.mute.chat")){
            $event->setCancelled();
            $event->getPlayer()->sendMessage($config->get("chat-error", "§8[§eChatMute§8] §cYou can't chat. Chat has been muted!"));
        }
    }
}

