<?php
/**
 * File "UserController.php"
 * @author Thomas Bourrely
 * 17/07/2017
 */

namespace mainApp\controllers;

use mainApp\models\User;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class UserController
 *
 * @package mainApp\controllers
 */
class UserController extends BaseController
{
    /**
     * Create a user
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     * @return static
     */
    public function create( RequestInterface $request, ResponseInterface $response, $args )
    {
        $params = $request->getParams();

        $user = User::firstOrCreate(
            array(
                'firstname' => $params['firstname'],
                'lastname' => $params['lastname'],
                'username' => $params['username'],
                'password' => password_hash( $params['password'], PASSWORD_DEFAULT )
            )
        );

        if ( $user->id ) {
            return $this->redirect( $response, 'home', $args );
        }

    }

    /**
     * Render form to create a user
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     */
    public function createForm( RequestInterface $request, ResponseInterface $response, $args )
    {
        return $this->render( $response, 'add_user' );
    }

    /**
     * Render form to login
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     */
    public function logInForm( RequestInterface $request, ResponseInterface $response, $args )
    {
        return $this->render( $response, 'log_in' );
    }

    /**
     * Login a user
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     * @return static
     */
    public function logIn( RequestInterface $request, ResponseInterface $response, $args )
    {
        $params = $request->getParams();

        $user = User::where('username', '=', $params['username'])->first();

        if ( $user ) {

            if ( password_verify( $params['password'], $user->password ) ) {
                $_SESSION['user_id'] = $user->id;
                return $this->redirect( $response, 'home' );
            }

        }

        return $this->redirect( $response, 'login.form' );

    }

    /**
     * Logout user
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     * @return static
     */
    public function logOut( RequestInterface $request, ResponseInterface $response, $args )
    {
        if ( !empty( $_SESSION['user_id'] ) ) {
            unset( $_SESSION['user_id'] );
        }

        return $this->redirect( $response, 'home' );
    }
}