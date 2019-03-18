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
     * @param int $postId
     *
     * @return bool|\PDOStatement
     */
    public function listForPost(int $postId)
    {
        return $this->commentManager->getComments($postId);
    }

    /**
     * Save a new comment
     *
     * @param int $postId
     *
     * @return bool
     */
    public function saveComment(int $postId)
    {
        if (!$this->commentManager->insertComment($postId, $this->sanitizedString('post', 'comment'), $this->getSessionVariable('user_id'))) {
            return false;
        }

        return (new PostController())->viewPost($postId);
    }

    /**
     * Displays the unvalidated comments
     *
     * @return bool
     */
    public function backofficeCommentsList()
    {
        return $this->render('backofficeCommentsList.html.twig', [
            'comments' => $this->commentManager->getUnvalidatedComments(),
            ]);
    }

    /**
     * Validate a comment
     *
     * @param int $commentId
     *
     * @return bool
     */
    public function validComment(int $commentId)
    {
        if (!$this->commentManager->validateComment($commentId)) {
            $this->setErrorMessage('Impossible d\'enregistrer le commentaire.');
            return false;
        }

        return $this->backofficeCommentsList();
    }

    /**
     * Delete a comment
     *
     * @param int $commentId
     *
     * @return bool
     */
    public function deleteComment(int $commentId)
    {
        if (!$this->commentManager->deleteComment($commentId)) {
            $this->setErrorMessage('Impossible de supprimer le commentaire.');
            return false;
        }

        return $this->backofficeCommentsList();
    }
}
