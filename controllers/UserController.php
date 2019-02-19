<?php
/**
 * Users Controller
 */

namespace OpenclassroomsP5\Controllers;

use OpenclassroomsP5\Models\UserManager;

/**
 * Class UserController
 * @package dbourni\OpenclassroomsP5
 */
class UserController extends Controller
{
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->userManager = new UserManager();
    }

    /**
     * Check the connection
     */
    public function connect()
    {
        $user = $this->userManager->getUserByName($_POST['name']);

        if (!password_verify($_POST['password'], $user['password'])) {
            $this->displayError('Votre nom ou votre mot de passe sont erronnés !');
            return;
        }

        $_SESSION['name'] = $_POST['name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_id'] = $user['user_id'];

        (new HomeController())->viewHome();
    }

    /**
     * Disconnect a user
     */
    public function disConnect()
    {
        $_SESSION['name'] = '';
        $_SESSION['role'] = '';
        (new HomeController())->viewHome();
    }

    /**
     * Displays the list of users
     */
    public function backofficeUsersList()
    {
        $users = $this->userManager->getUsers();

        $this->render('backofficeUsersList.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * Delete a user
     *
     * @param int $user_id
     */
    public function deleteUser(int $user_id)
    {
        if (!$this->userManager->deleteUser($user_id)) {
            $this->displayError('Une erreur s\'est produite durant la suppression de l\'utilisateur !');

            return;
        }

        $this->backofficeUsersList();
    }

    /**
     * Edit a user
     *
     * @param int $user_id
     */
    public function editUser(int $user_id)
    {
        $user = $this->userManager->getUser($user_id);

        $this->render('backofficeUserEdit.html.twig', [
            'user' => $user,
            'action' => 'index.php?p=updateUser',
            'header' => 'Modification de l\'utilisateur',
        ]);
    }

    /**
     * Update a user
     *
     * @param int $user_id
     */
    public function updateUser(int $user_id)
    {
        if (!$this->userManager->updateUser($user_id, $_POST['name'], $_POST['email'], $_POST['role'], password_hash($_POST['password'], PASSWORD_DEFAULT))) {
            $this->displayError('Une erreur s\'est produite !');
            return;
        }
        $this->backofficeUsersList();
    }

    /**
     * Create a new user
     */
    public function newUser()
    {
        $this->render('backofficeUserEdit.html.twig', [
            'action' => 'index.php?p=saveUser',
            'header' => 'Nouvel utilisateur',
        ]);
    }

    /**
     * Diplays the form for a new subscription
     */
    public function subscription()
    {
        $this->render('subscription.html.twig', [
            'header' => 'Inscription',
        ]);
    }

    /**
     * Save the subscription
     */
    public function saveSubscription()
    {
        if ($_POST['password'] != $_POST['password2']) {
            $this->displayError('Les mots de passe doivent être identiques !');
            return;
        }

        if ($this->userManager->getUserByName($_POST['name'])) {
            $this->displayError('Ce nom d\'utilisateur existe déjà !');
            return;
        }

        if (!$this->userManager->insertUser($_POST['name'], $_POST['email'], 'reader', password_hash($_POST['password'], PASSWORD_DEFAULT), FALSE)) {
            $this->displayError('Une erreur s\'est produite !');
            return;
        }

        (new HomeController())->viewHome();
    }

    /**
     * Save a new user
     */
    public function saveUser()
    {
        if (!$this->userManager->insertUser($_POST['name'], $_POST['email'], $_POST['role'], password_hash($_POST['password'], PASSWORD_DEFAULT), TRUE)) {
            $this->displayError('Une erreur s\'est produite !');
            return;
        }
        $this->backofficeUsersList();
    }

    /**
     * Valid a user by an admin
     *
     * @param int $user_id
     */
    public function validUser(int $user_id)
    {
        if (!$this->userManager->validateUser($user_id)) {
            $this->displayError('Une erreur s\'est produite !');

            return;
        }

        try {
            $user = $this->userManager->getUser($user_id);
            $to = $user['email'];
            $object = 'Validation de votre inscription';
            $message = 'Votre inscription a été validée.';
            mail($to, $object, $message);
        } catch (\Exception $e) {
            $this->displayError($e);
        }

        $this->backofficeUsersList();
    }

    /**
     * Displays the form for a forgotten pawword
     */
    public function forgottenPassword()
    {
        $this->render('forgottenPassword.html.twig', []);
    }

    /**
     * Create the code and send an email for reinit a password
     */
    public function initPassword()
    {
        if (!$this->userManager->getUserByName($_POST['name'])) {
            $this->displayError('Cet utilisateur n\'existe pas !');
            return;
        }

        $randCode = md5(uniqid('', true));

        if (!$this->userManager->setInitKey($_POST['name'], $randCode)) {
            $this->displayError('Une erreur est survenue !');
            return;
        }

        try {
            $user = $this->userManager->getUserByName($_POST['name']);
            $to = $user['email'];
            $object = 'Initialisation de votre mot de passe';
            $message = 'Pour initialiser votre mot de passe, cliquez sur ce lien : ';
            $message .= DOMAIN . '/index.php?p=modifyPassword&code=' . $randCode;
            mail($to, $object, $message);
        } catch (\Exception $e) {
            $this->displayError($e);
        }

        (new HomeController())->viewHome();
    }

    /**
     * Displays the form for modify a password
     * @param string $code
     */
    public function modifyPassword(string $code)
    {
        $this->render('modifyPassword.html.twig', [
            'code' => $code,
        ]);
    }

    /**
     * Save the modified password
     */
    public function saveNewPassword()
    {
        if ($_POST['password'] != $_POST['password2']) {
            $this->displayError('Les mots de passe doivent être identiques !');
            return;
        }

        $user = $this->userManager->getUserByName($_POST['name']);

        if ($user['init_key'] != $_POST['code']) {
            $this->displayError('Un problème de sécurité a été rencontré !');
            return;
        }

        if (!$this->userManager->setPassword($_POST['name'], password_hash($_POST['password'], PASSWORD_DEFAULT))) {
            $this->displayError('Une erreur est survenue lors de la mise à jour du mot de passe !');
            return;
        }

        (new HomeController())->viewConnect();
    }
}
