<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class BaseAuthorizeController
 * @package App\Controller
 */
class BaseAuthorizeController extends FOSRestController
{
    /**
     * Created By Nahla Sameh
     * Check if client email or username exist before
     * @param $email
     * @param $username
     * @return array
     */
    protected function _checkClientExist($email, $username)
    {
        /** @var ClientRepository $clientRepository */
        $clientRepository = $this->getDoctrine()->getRepository(Client::class);

        /* Check If User Email exist */
        $clientExist = $clientRepository->findOneBy(['email' => $email]);
        if ($clientExist !== null) {
            return array(
                'isRegistered' => false,
                'message' => 'Email exist before.');
        }

        /* Check If User Username exist */
        $clientExist = $clientRepository->findOneBy(['username' => $username]);
        if ($clientExist !== null) {
            return array(
                'isRegistered' => false,
                'message' => 'Username exist before.');
        }
        /* Email and username not exist before*/
        return array(
            'isRegistered' => true
        );
    }

    /**
     * Created By Nahla Sameh
     * Create Authentication token
     * @param $username
     * @return string
     */
    protected function _getAuthToken($username)
    {
        $session = new Session();
        //session->get('token');
        $token = md5(uniqid(rand(), true));
        $session->set('token', $token);
        $session->set('username', $username);
        return $token;
    }

    /**
     * Created By Nahla Sameh
     * Check if authentication token is right
     * @param $token
     * @return bool
     */
    protected function _checkAuthToken($token)
    {
        $session = new Session();
        if ($session->get('token') === $token) {
            return true;
        }
        return false;
    }

    /**
     * Created By Nahla Sameh
     * Remove Current client token
     */
    protected function _removeCurrentToken()
    {
        $session = new Session();
        $session->remove('token');
    }
}
