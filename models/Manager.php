<?php
/**
 * Main manager
 */

namespace dbourni\OpenclassroomsP5;

abstract class Manager
{
    protected $db;

    /**
     * Manager constructor.
     */
    public function __construct()
    {
        $this->db = new \PDO(DB_HOST, DB_USER, DB_PASSWORD);
    }
}
