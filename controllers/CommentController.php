<?php
/**
 * Comments controller
 */

namespace OpenclassroomsP5\Controllers;

use OpenclassroomsP5\Models\CommentManager;

/**
 * Class CommentController
 *
 * @package dbourni\OpenclassroomsP5
 */
class CommentController extends Controller
{
    /**
     * @var CommentManager
     */
    private $commentManager;

    /**
     * CommentController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->commentManager = new CommentManager();
    }

    /**
     * List the comment for a post
     *
     * @param int $post_id
     *
     * @return bool|\PDOStatement
     */
    public function listForPost(int $post_id)
    {
        return $this->commentManager->getComments($post_id);
    }

    /**
     * Save a new comment
     *
     * @param int $post_id
     */
    public function saveComment(int $post_id)
    {
        if (!$this->commentManager->insertComment($post_id, $_POST['comment'], $_SESSION['user_id'])) {
            $this->displayError('Une erreur s\'est produite !');

            return;
        }

        (new PostController())->viewPost($post_id);
    }

    /**
     * Displays the unvalidated comments
     */
    public function backofficeCommentsList()
    {
        $comments = $this->commentManager->getUnvalidatedComments();

        $this->render('backofficeCommentsList.html.twig', [
            'comments' => $comments,
        ]);
    }

    /**
     * Validate a comment
     *
     * @param int $comment_id
     */
    public function validComment(int $comment_id)
    {
        if (!$this->commentManager->validateComment($comment_id)) {
            $this->displayError('Une erreur s\'est produite !');

            return;
        }

        $this->backofficeCommentsList();
    }

    /**
     * Delete a comment
     *
     * @param int $comment_id
     */
    public function deleteComment(int $comment_id)
    {
        if (!$this->commentManager->deleteComment($comment_id)) {
            $this->displayError('Une erreur s\'est produite !');

            return;
        }

        $this->backofficeCommentsList();
    }
}
