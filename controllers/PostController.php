<?php
/**
 * Posts controller
 */

namespace dbourni\OpenclassroomsP5;

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
     */
    public function viewList()
    {
        $firstPost = isset($_GET['page']) ? ($_GET['page']*5)-5 : 0;

        $posts = $this->postManager->getPosts($firstPost, 5);

        $this->render('listposts.html.twig', [
            'title' => 'Blog de David - Blog',
            'posts' => $posts,
            'nbpages' => $this->nbPages(),
        ]);
    }

    /**
     * Display a single post
     *
     * @param int $id
     */
    public function viewPost(int $id)
    {
        $post = $this->postManager->getPost($id);

        $commentController = new CommentController();
        $comments = $commentController->listForPost($id);

        $this->render('viewpost.html.twig', [
            'title' => 'Blog de David - Blog',
            'post' => $post,
            'comments' => $comments,
            'post_id' => $id,
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

        $nbPages = round($nbPosts/5);
        if (0 !== ($nbPosts % 5)) {
            $nbPages++;
        }

        return $nbPages;
    }

    /**
     * Diplays the list of posts in the back office
     */
    public function backofficePostsList()
    {
        $posts = $this->postManager->getPosts(0, 20);

        $this->render('backofficePostsList.html.twig', [
            'title' => 'Blog de David - Back-Office',
            'posts' => $posts,
        ]);
    }

    /**
     * Create a new post
     */
    public function newPost()
    {
        $this->render('backofficePostEdit.html.twig', [
            'title' => 'Blog de David - Back-Office',
            'action' => 'index.php?p=savePost',
            'header' => 'Nouvel article',
        ]);
    }

    /**
     * Save a modified post
     */
    public function savePost()
    {
        $image = $this->uploadImage();

        if (!$this->postManager->insertPost($_POST['title'], $_POST['chapo'], $_POST['content'], 1, $image)) {
            $this->displayError('Une erreur s\'est produite !');
            return;
        }
        $this->backofficePostsList();
    }

    /**
     * Edit a post
     *
     * @param int $id
     */
    public function editPost(int $id)
    {
        $post = $this->postManager->getPost($id);

        $this->render('backofficePostEdit.html.twig', [
            'title' => 'Blog de David - Back-Office',
            'post' => $post,
            'action' => 'index.php?p=updatePost',
            'header' => 'Modification de l\'article',
        ]);
    }

    /**
     * Update a post
     *
     * @param int $id
     */
    public function updatePost(int $id)
    {
        $image = $this->uploadImage();

        if (!$this->postManager->updatePost($id, $_POST['title'], $_POST['chapo'], $_POST['content'], 1, $image)) {
            $this->displayError('Une erreur s\'est produite !');
            return;
        }
        $this->backofficePostsList();
    }

    /**
     * Delete a post
     *
     * @param int $id
     */
    public function deletePost(int $id)
    {
        // TODO Delete the comments from the deleted post

        if (!$this->postManager->deletePost($id)) {
            $this->displayError('Une erreur s\'est produite !');
            return;
        }
        $this->backofficePostsList();
    }

    /**
     * Upload an image from a form
     *
     * @return string
     */
    public function uploadImage()
    {
        if (null == $_FILES['uploadedFile']['name']) {
            return $_POST['image'];
        }

        $target_dir = 'public/uploads/';
        $target_file = $target_dir . basename($_FILES['uploadedFile']['name']);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        $check = getimagesize($_FILES['uploadedFile']['tmp_name']);
        if (!$check) {
            $uploadOk = 0; // The file is not an image
        }

        if (file_exists($target_file)) {
            $uploadOk = 0; // The file already exists
        }

        if ($_FILES['uploadedFile']['size'] > 500000) {
            $uploadOk = 0; // The file is too large
        }

        if($imageFileType != 'jpg' && $imageFileType != 'jpeg' ) {
            $uploadOk = 0; // The type is not accepted
        }

        if (1 == $uploadOk) {
            move_uploaded_file($_FILES['uploadedFile']['tmp_name'], $target_file);
        }

        return $target_file;
    }
}
