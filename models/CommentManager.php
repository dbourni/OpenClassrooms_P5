<?php
/**
 * Comments manager
 */

namespace OpenclassroomsP5\Models;

/**
 * Class CommentManager
 *
 * @package dbourni\OpenclassroomsP5
 */
class CommentManager extends Manager
{
    /**
     * Return the comments for a post
     *
     * @param int $post_id
     *
     * @return bool|\PDOStatement
     */
    public function getComments(int $post_id)
    {
        $req = $this->dbase->prepare('SELECT comments.id as comment_id, DATE_FORMAT(comments.date, \'%d %M %Y - %Hh%i\') AS comment_date, comments.comment, comments.user_id, comments.post_id, users.name AS users_id
                            FROM comments, users
                            WHERE comments.post_id = :postid AND users.id = comments.user_id AND comments.validated = 1
                            ORDER BY comment_date DESC');
        $req->bindParam(':postid', $post_id, \PDO::PARAM_INT);
        $req->execute();

        return $req;
    }

    /**
     * Return the unvalidated comments for a post
     *
     * @return bool|\PDOStatement
     */
    public function getUnvalidatedComments()
    {
        $req = $this->dbase->prepare('SELECT comments.id as comment_id, DATE_FORMAT(comments.date, \'%d %M %Y - %Hh%i\') AS comment_date, comments.comment, comments.user_id, comments.post_id, users.name AS users_name
                            FROM comments, users
                            WHERE users.id = comments.user_id AND comments.validated = 0
                            ORDER BY comment_date DESC');
        $req->execute();

        return $req;
    }

    /**
     * Validate a comment
     *
     * @param int $comment_id
     *
     * @return bool
     */
    public function validateComment(int $comment_id)
    {
        $validated = 1;

        $req = $this->dbase->prepare('UPDATE comments 
                                    SET validated = :validated
                                    WHERE comments.id = :id');
        $req->bindParam(':validated', $validated, \PDO::PARAM_INT);
        $req->bindParam(':id', $comment_id, \PDO::PARAM_INT);

        return $req->execute();
    }

    /**
     * Insert a new comment
     *
     * @param int $post_id
     * @param string $comment
     * @param int $user_id
     *
     * @return bool
     */
    public function insertComment(int $post_id, string $comment, int $user_id)
    {
        $comment_date = date("Y-m-d H:i:s");
        $validated = 0;

        $req = $this->dbase->prepare('INSERT INTO comments (user_id, post_id, date, comment, validated)
                                    VALUES (:user_id, :post_id, :comment_date, :comment, :validated)');
        $req->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
        $req->bindParam(':post_id', $post_id, \PDO::PARAM_INT);
        $req->bindParam(':comment_date', $comment_date);
        $req->bindParam(':comment', $comment, \PDO::PARAM_STR);
        $req->bindParam(':validated', $validated,\PDO::PARAM_INT);

        return $req->execute();
    }

    /**
     * Delate a comment
     *
     * @param int $comment_id
     *
     * @return bool
     */
    public function deleteComment(int $comment_id)
    {
        $req = $this->dbase->prepare('DELETE FROM comments 
                                    WHERE comments.id = :comment_id');
        $req->bindParam(':comment_id', $comment_id, \PDO::PARAM_INT);

        return $req->execute();
    }

    /**
     * Delete all the comments for a post
     *
     * @param int $post_id
     *
     * @return bool
     */
    public function deleteCommentsForPost(int $post_id)
    {
        $req = $this->dbase->prepare('DELETE FROM comments 
                                    WHERE comments.post_id = :post_id');
        $req->bindParam(':post_id', $post_id, \PDO::PARAM_INT);

        return $req->execute();
    }

    /**
     * Return the count of comments
     *
     * @return int
     */
    public function countComments()
    {
        $req = $this->dbase->query('SELECT COUNT(*) as nb
                            FROM comments');
        $data = $req->fetch();

        return intval($data['nb']) ?? 0;
    }
}
