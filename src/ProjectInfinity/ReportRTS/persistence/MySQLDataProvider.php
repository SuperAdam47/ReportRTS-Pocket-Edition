<?php

namespace ProjectInfinity\ReportRTS\persistence;

use ProjectInfinity\ReportRTS\ReportRTS;
use ProjectInfinity\ReportRTS\task\MySQLKeepAliveTask;

class MySQLDataProvider implements DataProvider {

    /** @var  ReportRTS */
    protected $plugin;

    /** @var  \mysqli */
    protected $database;

    /** @param ReportRTS $plugin */
    public function __construct(ReportRTS $plugin) {
        $this->plugin = $plugin;
        $config = $this->plugin->getConfig()->get("storage");

        if(!isset($config["host"])  or !isset($config["username"]) or !isset($config["password"])
        or !isset($config["database"])) {
            $this->plugin->getLogger()->critical("Your MySQL settings are invalid! Please check your config.yml");
            # Do as SimpleAuth and provide a dummy provider?
        }
        $this->database = new \mysqli($config["host"], $config["username"], $config["password"], $config["database"], isset($config["port"]) ? $config["port"] : 3306);
        if($this->database->connect_error) {
            $this->plugin->getLogger()->critical("Could not connect to MySQL! Cause: ".$this->database->connect_error);
            # Do as SimpleAuth and provide a dummy provider?
            return;
        }

        $resource = $this->plugin->getResource("mysql_tickets.sql");
        $this->database->query(stream_get_contents($resource));
        # Make sure connection stays alive.
        $this->plugin->getServer()->getScheduler()->scheduleRepeatingTask(new MySQLKeepAliveTask($this->plugin, $this->database), 600);

        $this->plugin->getLogger()->info("Connected using MySQL");
    }

    public function close()
    {
        // TODO: Implement close() method.
    }

    public function createUser($username)
    {
        // TODO: Implement createUser() method.
    }

    public function createTicket($staffId, $world, $x, $y, $z, $message, $userId, $timestamp)
    {
        // TODO: Implement createTicket() method.
    }

    public function countHeldTickets()
    {
        // TODO: Implement countHeldTickets() method.
    }

    public function countTickets()
    {
        // TODO: Implement countTickets() method.
    }

    public function deleteEntry($table, $id)
    {
        // TODO: Implement deleteEntry() method.
    }

    public function getUserId($username)
    {
        // TODO: Implement getUserId() method.
    }

    public function getLastIdBy($username)
    {
        // TODO: Implement getLastIdBy() method.
    }

    public function getTickets($cursor, $limit, $status)
    {
        // TODO: Implement getTickets() method.
    }

    public function getTicketById($id)
    {
        // TODO: Implement getTicketById() method.
    }

    public function getLocation($id)
    {
        // TODO: Implement getLocation() method.
    }

    public function getUnnotifiedUsers()
    {
        // TODO: Implement getUnnotifiedUsers() method.
    }

    public function getEverything($table)
    {
        // TODO: Implement getEverything() method.
    }

    public function getHandledBy($username)
    {
        // TODO: Implement getHandledBy() method.
    }

    public function getOpenedBy($username)
    {
        // TODO: Implement getOpenedBy() method.
    }

    public function getStats()
    {
        // TODO: Implement getStats() method.
    }

    public function getUsername($userId)
    {
        // TODO: Implement getUsername() method.
    }

    public function setTicketStatus($id, $username, $status, $comment, $notified, $timestamp)
    {
        // TODO: Implement setTicketStatus() method.
    }

    public function setNotificationStatus($id, $status)
    {
        // TODO: Implement setNotificationStatus() method.
    }

    public function setUserStatus($username, $status)
    {
        // TODO: Implement setUserStatus() method.
    }

    public function populateTicketArray()
    {
        // TODO: Implement populateTicketArray() method.
    }

    public function userExists($player)
    {
        // TODO: Implement userExists() method.
    }

    public function updateTicket($id)
    {
        // TODO: Implement updateTicket() method.
    }

    public function openTicket()
    {
        // TODO: Implement openTicket() method.
    }
}