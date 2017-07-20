<?php
/**
 * File "HomeController.php"
 * @author Thomas Bourrely
 * 17/07/2017
 */

namespace mainApp\controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use \mainApp\models\User;


/**
 * Class HomeController
 *
 * @package mainApp\controllers
 */
class HomeController extends BaseController
{
    /**
     * Respond to homepage route
     * Render html
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     */
    public function home( RequestInterface $request, ResponseInterface $response, $args )
    {
        $users = User::all();

        $render_args = array();

        $render_args['users'] = $users;

        if ( !empty( $_SESSION['user_id'] ) ) {
            $user = User::where( 'id', '=', $_SESSION['user_id'] )->first();

            if ( $user ) {
                $render_args['connected_user'] = $user;
            }
        }

        return $this->render( $response, 'home', $render_args );
    }

}