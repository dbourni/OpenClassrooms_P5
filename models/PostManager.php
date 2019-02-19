<?php
/**
 * Posts manager
 */

namespace OpenclassroomsP5\models;

/**
 * Class PostManager
 *
 * @package dbourni\OpenclassroomsP5
 */
class PostManager extends Manager
{
    /**
     * Return the 5 last posts
     *
     * @param int $firstPost
     * @param int $nbPosts
     *
     * @return bool|\PDOStatement
     */
    public function getPosts(int $firstPost, int $nbPosts)
    {
        $req = $this->db->prepare('SELECT posts.id as id_post, posts.title, posts.chapo, posts.author_id, DATE_FORMAT(posts.date, \'%d %M %Y - %Hh%i\') AS date_post, posts.content, posts.image, users.id, users.name
                            FROM posts, users
                            WHERE users.id = posts.author_id
                            ORDER BY date_post DESC
                            LIMIT :firstPost, :nbPosts');
        $req->bindParam(':firstPost', $firstPost, \PDO::PARAM_INT);
        $req->bindParam(':nbPosts', $nbPosts, \PDO::PARAM_INT);
        $req->execute();

        return $req;
    }

    /**
     * Return the count of posts
     *
     * @return int
     */
    public function countPosts()
    {
        $req = $this->db->query('SELECT COUNT(*) as nb
                            FROM posts');
        $data = $req->fetch();

        return intval($data['nb']) ?? 0;
    }

    /**
     * Return a single post
     *
     * @param int $id
     *
     * @return mixed
     */
    public function getPost(int $id) {
        $req = $this->db->prepare('SELECT posts.id as id_post, posts.title, posts.chapo, posts.author_id, DATE_FORMAT(posts.date, \'%d %M %Y\') AS date_post, posts.content, posts.image, users.id, users.name
                            FROM posts, users
                            WHERE users.id = posts.author_id AND posts.id = :id_post');
        $req->bindParam(':id_post', $id, \PDO::PARAM_INT);
        $req->execute();

        return $req->fetch();
    }

    /**
     * Insert a new post
     *
     * @param string $title
     * @param string $chapo
     * @param string $content
     * @param int $author_id
     * @param string $image
     *
     * @return bool
     */
    public function insertPost(string $title, string $chapo, string $content, int $author_id, string $image)
    {
        $date_post = date("Y-m-d H:i:s");

        $req = $this->db->prepare('INSERT INTO posts (title, chapo, author_id, date, content, image)
                                    VALUES (:title, :chapo, :author_id, :date_post, :content, :image)');
        $req->bindParam(':title', $title, \PDO::PARAM_STR);
        $req->bindParam(':chapo', $chapo, \PDO::PARAM_STR);
        $req->bindParam(':author_id', $author_id, \PDO::PARAM_INT);
        $req->bindParam(':date_post', $date_post);
        $req->bindParam(':content', $content, \PDO::PARAM_STR);
        $req->bindParam(':image', $image, \PDO::PARAM_STR);

        return $req->execute();
    }

    /**
     * Update a post
     *
     * @param int $post_id
     * @param string $title
     * @param string $chapo
     * @param string $content
     * @param int $author_id
     * @param string $image
     *
     * @return bool
     */
    public function updatePost(int $post_id, string $title, string $chapo, string $content, int $author_id, string $image)
    {
        $date_post = date('Y-m-d H:i:s');

        $req = $this->db->prepare('UPDATE posts 
                                    SET title = :title, chapo = :chapo, author_id = :author_id, date = :date_post, content = :content, image = :image
                                    WHERE posts.id = :post_id');
        $req->bindParam(':title', $title, \PDO::PARAM_STR);
        $req->bindParam(':chapo', $chapo, \PDO::PARAM_STR);
        $req->bindParam(':author_id', $author_id, \PDO::PARAM_INT);
        $req->bindParam(':date_post', $date_post);
        $req->bindParam(':content', $content, \PDO::PARAM_STR);
        $req->bindParam(':image', $image, \PDO::PARAM_STR);
        $req->bindParam(':post_id', $post_id, \PDO::PARAM_INT);

        return $req->execute();
    }

    /**
     * Delete a post
     *
     * @param int $post_id
     *
     * @return bool
     */
    public function deletePost(int $post_id)
    {
        $req = $this->db->prepare('DELETE FROM posts 
                                    WHERE posts.id = :post_id');
        $req->bindParam(':post_id', $post_id, \PDO::PARAM_INT);

        return $req->execute();
    }
}

