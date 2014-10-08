<?php

namespace ProjectInfinity\ReportRTS\command\sub;

use pocketmine\command\CommandSender;
use ProjectInfinity\ReportRTS\ReportRTS;
use ProjectInfinity\ReportRTS\util\MessageHandler;
use ProjectInfinity\ReportRTS\util\PermissionHandler;
use ProjectInfinity\ReportRTS\util\ToolBox;

class ClaimTicket {

    private $plugin;
    private $data;

    public function __construct(ReportRTS $plugin) {
        $this->plugin = $plugin;
        $this->data = $plugin->getDataProvider();
    }

    public function handleCommand(CommandSender $sender, $args) {

        ### Check if anything is wrong with the provided input before going further. ###
        if(!PermissionHandler::canClaimTicket) {
            $sender->sendMessage(sprintf(MessageHandler::$permissionError, PermissionHandler::canClaimTicket));
            return true;
        }

        if(count($args) < 2) {
            $sender->sendMessage(sprintf(MessageHandler::$generalError, "You need to specify a ticket ID."));
            return true;
        }

        if(!ToolBox::isNumber($args[1])) {
            $sender->sendMessage(sprintf(MessageHandler::$generalError, "Ticket ID must be a number. Provided: ".$args[1]));
            return true;
        }

        $ticketId = intval($args[1]);

        if(!isset(ReportRTS::$tickets[$ticketId])) {
            # The ticket that the user is trying to claim is not in the array (not open).
            $sender->sendMessage(MessageHandler::$ticketNotOpen);
            return true;
        }
        ### We're done! Let's start processing stuff. ###

        $ticket = ReportRTS::$tickets[$args[1]];

        $timestamp = microtime(true) * 1000;

        if(!$this->data->setTicketStatus($ticketId, $sender->getName(), 1, null, 0, $timestamp)) {
            $sender->sendMessage(sprintf(MessageHandler::$generalError, "Unable to claim ticket #".$ticketId));
            return true;
        }

        $player = $this->plugin->getServer()->getPlayer($ticket->getName());
        if($player != null) {
            $player->sendMessage(MessageHandler::$ticketClaimUser, $sender->getName());
            $player->sendMessage(MessageHandler::$ticketClaimText, $ticket->getMessage());
        }

        # Let staff know about this change.
        $this->plugin->messageStaff(sprintf(MessageHandler::$ticketClaim, $sender->getName(), $ticketId));

        return true;
    }
}