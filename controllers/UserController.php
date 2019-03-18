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
     *
     * @return bool
     */
    public function connect()
    {
        $postName = $this->sanitizedString('post', 'name');
        $postPassword = $this->sanitizedString('post', 'password');

        $user = $this->userManager->getUserByName($postName);

        if (!password_verify($postPassword, $user['password'])) {
            $this->setErrorMessage('L\'utilisateur ou le mot de passe sont incorrects');
            return false;
        }

        $this->setSessionVariable('name', $postName);
        $this->setSessionVariable('role', $user['role']);
        $this->setSessionVariable('user_id', $user['user_id']);

        return (new HomeController())->viewHome();
    }

    /**
     * Disconnect a user
     *
     * @return bool
     */
    public function disConnect()
    {
        session_destroy();
        return (new HomeController())->viewHome();
    }

    /**
     * Displays the list of users
     *
     * @return bool
     */
    public function backofficeUsersList()
    {
          return $this->render('backofficeUsersList.html.twig', [
            'users' => $this->userManager->getUsers(),
        ]);
    }

    /**
     * Delete a user
     *
     * @param int $userId
     *
     * @return bool
     */
    public function deleteUser(int $userId)
    {
        if (!$this->userManager->deleteUser($userId)) {
            $this->setErrorMessage('Impossible de supprimer l\'utilisateur.');
            return false;
        }

        return $this->backofficeUsersList();
    }

    /**
     * Edit a user
     *
     * @param int $userId
     *
     * @return bool
     */
    public function editUser(int $userId)
    {
        $user = $this->userManager->getUser($userId);

        if (!$user) {
            $this->setErrorMessage('L\'utilisateur n\'existe pas.');
            return false;
        }

        return $this->render('backofficeUserEdit.html.twig', [
            'user' => $user,
            'action' => 'index.php?p=updateUser',
            'header' => 'Modification de l\'utilisateur',
        ]);
    }

    /**
     * Update a user
     *
     * @param int $userId
     *
     * @return bool
     */
    public function updateUser(int $userId)
    {
        $postEmail = $this->sanitizedEmail('post', 'email');
        $newPassword = $this->sanitizedString('post', 'password');
        $postName = $this->sanitizedString('post', 'name');
        $postRole = $this->sanitizedString('post', 'role');

        if ($newPassword) {
            $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        if (!$this->userManager->updateUser($userId, $postName, $postEmail, $postRole, $newPassword)) {
            $this->setErrorMessage('Impossible de mettre à jour l\'utilisateur.');
            return false;
        }

        return $this->backofficeUsersList();
    }

    /**
     * Create a new user
     *
     * @return bool
     */
    public function newUser()
    {
        return $this->render('backofficeUserEdit.html.twig', [
            'action' => 'index.php?p=saveUser',
            'header' => 'Nouvel utilisateur',
        ]);
    }

    /**
     * Diplays the form for a new subscription
     *
     * @return bool
     */
    public function subscription()
    {
        return $this->render('subscription.html.twig', [
            'header' => 'Inscription',
        ]);
    }

    /**
     * Save the subscription
     *
     * @return bool
     */
    public function saveSubscription()
    {
        $postEmail = $this->sanitizedEmail('post', 'email');
        $postPassword = $this->sanitizedString('post', 'password');
        $postPassword2 = $this->sanitizedString('post', 'password2');
        $postName = $this->sanitizedString('post', 'name');

        if ($postPassword != $postPassword2) {
            $this->setErrorMessage('Les mots de passe doivent être identiques !');
            return false;
        }

        if ($this->userManager->getUserByName($postName)) {
            $this->setErrorMessage('Ce nom d\'utilisateur existe déjà !');
            return false;
        }

        if (!$this->userManager->insertUser($postName, $postEmail, 'reader', password_hash($postPassword, PASSWORD_DEFAULT), FALSE)) {
            $this->setErrorMessage('Impossible d\'enregistrer l\'utilisateur.');
            return false;
        }

        return (new HomeController())->viewHome();
    }

    /**
     * Save a new user
     *
     * @return bool
     */
    public function saveUser()
    {
        $postEmail = $this->sanitizedEmail('post', 'email');
        $newPassword = $this->sanitizedString('post', 'password');
        $postName = $this->sanitizedString('post', 'name');
        $postRole = $this->sanitizedString('post', 'role');

        if (!$this->userManager->insertUser($postName, $postEmail, $postRole, password_hash($newPassword, PASSWORD_DEFAULT), TRUE)) {
            $this->setErrorMessage('Impossible d\'enregistrer l\'utilisateur.');
            return false;
        }

        return $this->backofficeUsersList();
    }

    /**
     * Valid a user by an admin
     *
     * @param int $userId
     *
     * @return bool
     */
    public function validUser(int $userId)
    {
        if (!$this->userManager->validateUser($userId)) {
            $this->setErrorMessage('Impossible de valider l\'utilisateur.');
            return false;
        }

        try {
            $user = $this->userManager->getUser($userId);
            $recipient = $user['email'];
            $object = 'Validation de votre inscription';
            $message = 'Votre inscription a été validée.';
            mail($recipient, $object, $message);
        } catch (\Exception $exception) {
            $this->setErrorMessage('Impossible d\'envoyer l\'email.');
            return false;
        }

        return $this->backofficeUsersList();
    }

    /**
     * Displays the form for a forgotten password
     *
     * @return bool
     */
    public function forgottenPassword()
    {
        return $this->render('forgottenPassword.html.twig', []);
    }

    /**
     * Create the code and send an email for reinit a password
     *
     * @return bool
     */
    public function initPassword()
    {
        $postName = $this->sanitizedString('post', 'name');

        if (!$this->userManager->getUserByName($postName)) {
            $this->setErrorMessage('Cet utilisateur n\'existe pas !');
            return false;
        }

        $randCode = md5(uniqid('', true));

        if (!$this->userManager->setInitKey($postName, $randCode)) {
            $this->setErrorMessage('Impossible de générer la clé de sécurité !');
            return false;
        }

        try {
            $user = $this->userManager->getUserByName($postName);
            $recipient = $user['email'];
            $object = 'Initialisation de votre mot de passe';
            $message = 'Pour initialiser votre mot de passe, cliquez sur ce lien : ';
            $message .= DOMAIN . '/index.php?p=modifyPassword&code=' . $randCode;
            mail($recipient, $object, $message);
        } catch (\Exception $exception) {
            $this->setErrorMessage('Impossible d\'envoyer l\'email.');
            return false;
        }

        return (new HomeController())->viewHome();
    }

    /**
     * Displays the form for modify a password
     *
     * @param string $code
     *
     * @return bool
     */
    public function modifyPassword(string $code)
    {
        return $this->render('modifyPassword.html.twig', [
            'code' => $code,
        ]);
    }

    /**
     * Save the modified password
     *
     * @return bool
     */
    public function saveNewPassword()
    {
        $postPassword = $this->sanitizedString('post', 'password');
        $postPassword2 = $this->sanitizedString('post', 'password2');
        $postName = $this->sanitizedString('post', 'name');
        $postCode = $this->sanitizedString('post', 'code');

        if ($postPassword != $postPassword2) {
            $this->setErrorMessage('Les mots de passe doivent être identiques !');
            return false;
        }

        $user = $this->userManager->getUserByName($postName);

        if ($user['init_key'] != $postCode) {
            $this->setErrorMessage('Un problème de sécurité a été rencontré !');
            return false;
        }

        if (!$this->userManager->setPassword($postName, password_hash($postPassword, PASSWORD_DEFAULT))) {
            $this->setErrorMessage('Une erreur est survenue lors de la mise à jour du mot de passe !');
            return false;
        }

        return (new HomeController())->viewConnect();
    }
}
