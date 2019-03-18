<?php
/**
 * Main manager
 */

namespace OpenclassroomsP5\Models;

use PDO;

/**
 * Class Manager
 *
 * @package dbourni\OpenclassroomsP5
 */
abstract class Manager
{
    protected $dbase;

    /**
     * Manager constructor.
     */
    public function __construct()
    {
        $this->dbase = new PDO(DB_HOST, DB_USER, DB_PASSWORD);
    }
}
