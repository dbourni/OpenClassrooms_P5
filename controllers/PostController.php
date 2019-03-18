<?php
/**
 * Posts controller
 */

namespace OpenclassroomsP5\Controllers;

use OpenclassroomsP5\Models\CommentManager;
use OpenclassroomsP5\Models\PostManager;
use OpenclassroomsP5\Models\UserManager;

/**
 * Class PostController
 *
 * @package dbourni\OpenclassroomsP5
 */
class PostController extends Controller
{
    /**
     * @var PostManager
     */
    private $postManager;

    /**
     * PostController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->postManager = new PostManager();
    }

    /**
     * Display the list of posts
     *
     * @return bool
     */
    public function viewList()
    {
        $getPage = $this->sanitizedString('get', 'page');
        $firstPost = isset($getPage) ? ($getPage*5)-5 : 0;

        return $this->render('listposts.html.twig', [
            'posts' => $this->postManager->getPosts($firstPost, 5),
            'nbpages' => $this->nbPages(),
        ]);
    }

    /**
     * Display a single post
     *
     * @param int $postId
     *
     * @return bool
     */
    public function viewPost(int $postId)
    {
        $post = $this->postManager->getPost($postId);

        if (!$post) {
            $this->setErrorMessage('La page n\'existe pas.');
            return false;
        }

        return $this->render('viewpost.html.twig', [
            'post' => $post,
            'comments' => (new CommentController())->listForPost($postId),
            'post_id' => $postId,
        ]);
    }

    /**
     * Return the number of posts
     *
     * @return float|int
     */
    public function nbPages()
    {
        $nbPosts = $this->postManager->countPosts();

        $nbPages = floor($nbPosts/5);
        if (0 !== ($nbPosts % 5)) {
            $nbPages++;
        }

        return $nbPages;
    }

    /**
     * Diplays the list of posts in the back office
     *
     * @return bool
     */
    public function backofficePostsList()
    {
        return $this->render('backofficePostsList.html.twig', [
            'posts' => $this->postManager->getPosts(0, 20),
        ]);
    }

    /**
     * Create a new post
     *
     * @return bool
     */
    public function newPost()
    {
        return $this->render('backofficePostEdit.html.twig', [
            'users' => (new UserManager())->getUsers(),
            'userId' => $this->getSessionVariable('user_id'),
            'action' => 'index.php?p=savePost',
            'header' => 'Nouvel article',
        ]);
    }

    /**
     * Save a modified post
     *
     * @return bool
     */
    public function savePost()
    {
        $postTitle = $this->sanitizedString('post', 'title');
        $postChapo = $this->sanitizedString('post', 'chapo');
        $postContent = $this->sanitizedString('post', 'content');

        if (!$this->postManager->insertPost($postTitle, $postChapo, $postContent, $this->getSessionVariable('user_id'), $this->uploadImage())) {
            $this->setErrorMessage('Impossible d\'enregistrer le billet.');
            return false;
        }

        return $this->backofficePostsList();
    }

    /**
     * Edit a post
     *
     * @param int $PostId
     *
     * @return bool
     */
    public function editPost(int $postId)
    {
        $post = $this->postManager->getPost($postId);

        if (!$post) {
            $this->setErrorMessage('Le billet n\'existe pas.');
            return false;
        }

        return $this->render('backofficePostEdit.html.twig', [
            'post' => $post,
            'users' => (new UserManager())->getUsers(),
            'action' => 'index.php?p=updatePost',
            'header' => 'Modification de l\'article',
        ]);
    }

    /**
     * Update a post
     *
     * @param int $PostId
     *
     * @return bool
     */
    public function updatePost(int $postId)
    {
        $postTitle = $this->sanitizedString('post', 'title');
        $postChapo = $this->sanitizedString('post', 'chapo');
        $postContent = $this->sanitizedString('post', 'content');
        $postAuthor = $this->sanitizedString('post', 'author');

        $image = $this->uploadImage();

        if (!$this->postManager->updatePost($postId, $postTitle, $postChapo, $postContent, $postAuthor, $image)) {
            $this->setErrorMessage('Impossible de mettre à jour le billet.');
            return false;
        }

        return $this->backofficePostsList();
    }

    /**
     * Delete a post
     *
     * @param int $PostId
     *
     * @return bool
     */
    public function deletePost(int $postId)
    {
        if (!$this->postManager->getPost($postId)) {
            $this->setErrorMessage('Le billet n\'existe pas.');
            return false;
        }

        if (!$this->postManager->deletePost($postId)) {
            $this->setErrorMessage('Impossible de supprimer le billet');
            return false;
        }

        // Delete the comments for this post
        if (!(new CommentManager())->deleteCommentsForPost($postId)) {
            $this->setErrorMessage('Impossible de supprimer les commentaires liés au billet.');
            return false;
        }

        return $this->backofficePostsList();
    }

    /**
     * Upload an image from a form
     *
     * @return string
     */
    public function uploadImage()
    {
        $postImage = filter_input(INPUT_POST, 'image');

        if (isset($_FILES['uploadedFile']) AND null == filter_var($_FILES['uploadedFile']['name'], FILTER_SANITIZE_URL	)) {
            return $postImage;
        }

        $target_file = 'public/uploads/' . basename(filter_var($_FILES['uploadedFile']['name'], FILTER_SANITIZE_URL	));
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        $check = getimagesize(filter_var($_FILES['uploadedFile']['tmp_name'], FILTER_SANITIZE_URL	));
        if (!$check) {
            $uploadOk = 0; // The file is not an image
        }

        if (file_exists($target_file)) {
            $uploadOk = 0; // The file already exists
        }

        if ($_FILES['uploadedFile']['size'] > 1500000) {
            $uploadOk = 0; // The file is too large
        }

        if($imageFileType != 'jpg' && $imageFileType != 'jpeg' ) {
            $uploadOk = 0; // The type is not accepted
        }

        if (1 === $uploadOk) {
            move_uploaded_file($_FILES['uploadedFile']['tmp_name'], $target_file);
        }

        return $target_file;
    }
}
