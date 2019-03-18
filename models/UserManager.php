<?php
/**
 * Users manager
 */

namespace OpenclassroomsP5\Models;


/**
 * Class UserManager
 *
 * @package dbourni\OpenclassroomsP5
 */
class UserManager extends Manager
{
    /**
     * Return all the users
     *
     * @return bool|\PDOStatement
     */
    public function getUsers()
    {
        $req = $this->dbase->prepare('SELECT users.id as user_id, users.name, users.email, users.role, users.password, users.validated
                            FROM users
                            ORDER BY users.name');
        $req->execute();

        return $req;
    }

    /**
     * Return one user with his id
     *
     * @param int $user_id
     *
     * @return mixed
     */
    public function getUser(int $userId) {
        $req = $this->dbase->prepare('SELECT users.id as user_id, users.name, users.email, users.role, users.password
                            FROM users
                            WHERE users.id = :user_id');
        $req->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $req->execute();

        return $req->fetch();
    }

    /**
     * Return one user with his name
     *
     * @param string $user_name
     *
     * @return mixed
     */
    public function getUserByName(string $userName) {
        $req = $this->dbase->prepare('SELECT users.id as user_id, users.name, users.email, users.role, users.password, users.init_key
                            FROM users
                            WHERE users.name = :user_name');
        $req->bindParam(':user_name', $userName, \PDO::PARAM_STR);
        $req->execute();

        return $req->fetch();
    }

    /**
     * Delete a user
     *
     * @param int $user_id
     *
     * @return bool
     */
    public function deleteUser(int $userId)
    {
        $req = $this->dbase->prepare('DELETE FROM users 
                                    WHERE users.id = :user_id');
        $req->bindParam(':user_id', $userId, \PDO::PARAM_INT);

        return $req->execute();
    }

    /**
     * Update a user
     *
     * @param int $user_id
     * @param string $name
     * @param string $email
     * @param string $role
     * @param string $password
     *
     * @return bool
     */
    public function updateUser(int $userId, string $name, string $email, string $role, string $password)
    {
        $req = $this->dbase->prepare('UPDATE users 
                                        SET users.name = :name, users.email = :email, users.role = :role, users.password = :password
                                        WHERE users.id = :user_id');

        if (!$password) {
            $req = $this->dbase->prepare('UPDATE users 
                                        SET users.name = :name, users.email = :email, users.role = :role
                                        WHERE users.id = :user_id');
        }
        $req->bindParam(':name', $name, \PDO::PARAM_STR);
        $req->bindParam(':email', $email, \PDO::PARAM_STR);
        $req->bindParam(':role', $role, \PDO::PARAM_STR);
        if ($password) {
            $req->bindParam(':password', $password, \PDO::PARAM_STR);
        }
        $req->bindParam(':user_id', $userId, \PDO::PARAM_INT);

        return $req->execute();
    }

    /**
     * Create a new user
     *
     * @param string $name
     * @param string $email
     * @param string $role
     * @param string $password
     * @param int $validated
     *
     * @return bool
     */
    public function insertUser(string $name, string $email, string $role, string $password, int $validated)
    {
        $req = $this->dbase->prepare('INSERT INTO users (name, email, role, password, validated)
                                    VALUES (:name, :email, :role, :password, :validated)');
        $req->bindParam(':name', $name, \PDO::PARAM_STR);
        $req->bindParam(':email', $email, \PDO::PARAM_STR);
        $req->bindParam(':role', $role, \PDO::PARAM_STR);
        $req->bindParam(':password', $password, \PDO::PARAM_STR);
        $req->bindParam(':validated', $validated, \PDO::PARAM_INT);

        return $req->execute();
    }

    /**
     * Valid a user
     *
     * @param int $user_id
     *
     * @return bool
     */
    public function validateUser(int $userId)
    {
        $validated = 1;

        $req = $this->dbase->prepare('UPDATE users 
                                    SET validated = :validated
                                    WHERE users.id = :id');
        $req->bindParam(':validated', $validated, \PDO::PARAM_INT);
        $req->bindParam(':id', $userId, \PDO::PARAM_INT);

        return $req->execute();
    }

    /**
     * Set the init key after a forgotten password
     *
     * @param string $user_name
     * @param string $init_key
     *
     * @return bool
     */
    public function setInitKey(string $userName, string $initKey)
    {
        $req = $this->dbase->prepare('UPDATE users 
                                    SET users.init_key = :init_key
                                    WHERE users.name = :user_name');
        $req->bindParam(':init_key', $initKey, \PDO::PARAM_STR);
        $req->bindParam(':user_name', $userName, \PDO::PARAM_STR);

        return $req->execute();
    }

    /**
     * Set the password afetr a forgotten password
     *
     * @param string $user_name
     * @param string $password
     *
     * @return bool
     */
    public function setPassword(string $userName, string $password)
    {
        $initKey = '';

        $req = $this->dbase->prepare('UPDATE users 
                                    SET users.password = :password, users.init_key = :init_key
                                    WHERE users.name = :user_name');
        $req->bindParam(':password', $password, \PDO::PARAM_STR);
        $req->bindParam(':init_key', $initKey, \PDO::PARAM_STR);
        $req->bindParam(':user_name', $userName, \PDO::PARAM_STR);

        return $req->execute();
    }

    /**
     * Return the users number
     *
     * @return int
     */
    public function countUsers()
    {
        $req = $this->dbase->query('SELECT COUNT(*) as nb
                            FROM users');
        $data = $req->fetch();

        return intval($data['nb']) ?? 0;
    }
}