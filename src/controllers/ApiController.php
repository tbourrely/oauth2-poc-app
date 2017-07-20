<?php
/**
 * File "ApiController.php"
 * @author Thomas Bourrely
 * 19/07/2017
 */

namespace mainApp\controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

use mainApp\models\User;
use mainApp\models\Client;

/**
 * Class ApiController
 *
 * @package mainApp\controllers
 */
class ApiController extends BaseController
{
    /**
     * API endpoint
     * Return users list
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     * @return mixed|string|void
     */
    public function userInfos( RequestInterface $request, ResponseInterface $response, $args )
    {
        $users = User::all();

        $users_list = array();

        foreach ( $users as $user ) {
            $infos = array(
                'lastname' => $user->lastname,
                'firstname' => $user->firstname,
                'username' => $user->username
            );
            array_push( $users_list, $infos );
        }

        return json_encode( $users_list );
    }

    /**
     * Form to add a client
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     */
    public function addClientForm( RequestInterface $request, ResponseInterface $response, $args )
    {
        return $this->render( $response, 'api/add_client' );
    }

    /**
     * Process data received from addClientForm
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     */
    public function addClient( RequestInterface $request, ResponseInterface $response, $args )
    {
        $params = $request->getParams();

        $errors = [];

        if ( empty( $params['app_name'] ) ) {
            $errors['app_name'] = "Erreur";
        }

        if ( empty( $params['redirect_uri'] ) ) {
            $errors['redirect_uri'] = "Erreur";
        }

        $client = Client::firstOrNew(['name' => $params['app_name']]);

        if ( !empty( $client->client_id ) )
            $errors['app_name'] = "Erreur, nom déja utilisé";

        if ( empty( $errors ) ) {
            $client->client_id = uniqid();
            $client->client_secret = md5(uniqid());
            $client->redirect_uri = $params['redirect_uri'];

            $client->save();

            return $this->render( $response, 'api/client_created', [ 'client' => $client ] );

        }

        return $this->render( $response, 'api/add_client', [ 'errors' => $errors ] );
    }

    /**
     * Render login page
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     */
    public function login( RequestInterface $request, ResponseInterface $response, $args )
    {
        $params = $request->getParams();
        $render_args = [];

        if ( !empty( $params['redirect_uri'] ) )
            $render_args['uri'] = $params['redirect_uri'];

        if ( !empty( $params['status'] ) )
            $render_args['status'] = $params['status'];

        return $this->render( $response, 'api/log_in', $render_args );
    }
}