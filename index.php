<?php
/**
 * File "index.php"
 * @author Thomas Bourrely
 * 17/07/2017
 */

require_once __DIR__ . '/vendor/autoload.php';

use mainApp\DatabaseFactory;


session_start();


/******************
 * DATABASE CONNECTION
 *****************/

DatabaseFactory::setConfig();
DatabaseFactory::makeConnection();

/******************
 * END OF DATABASE CONNECTION
 *****************/


$app = new Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);


// Get container
$container = $app->getContainer();

// Register twig-view on container
$container['views'] = function( $container ) {
    $view = new \Slim\Views\Twig('src/views', [
        'cache' => false // disable cache
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};


/******************
 * START OF ROUTES
 *****************/

$app->get( '/', \mainApp\controllers\HomeController::class . ':home' )->setName('home');

$app->get( '/create_user', \mainApp\controllers\UserController::Class . ':createForm' )->setName('createUser.form');
$app->post( '/create_user', \mainApp\controllers\UserController::Class . ':create' )->setName('createUser.post');

$app->get( '/login', \mainApp\controllers\UserController::class . ':logInForm' )->setName('login.form');
$app->post( '/login', \mainApp\controllers\UserController::class . ':logIn' )->setName('login.post');

$app->get( '/logout', \mainApp\controllers\UserController::class . ':logOut' )->setName('logout');

/******************
 * END OF ROUTES
 *****************/


/**************
 *    API
 *************/
$app->group( '/api', function() {

    $accessTokenRepository = new \mainApp\repositories\AccessTokenRepository();
    $publicKey = __DIR__ . '/../oauth2-poc-server/public.key';
    $server = new \League\OAuth2\Server\ResourceServer(
        $accessTokenRepository,
        $publicKey
    );
    $oauth_middleware = new \League\OAuth2\Server\Middleware\ResourceServerMiddleware( $server );

    $this->get( '/user_infos', \mainApp\controllers\ApiController::class . ':userInfos' )->add( $oauth_middleware )->setName('API_userInfos');

    $this->get( '/register_client', \mainApp\controllers\ApiController::class . ':addClientForm' )->setName('API_addClient.form');
    $this->post( '/register_client', \mainApp\controllers\ApiController::class . ':addClient' )->setName('API_addClient.post');

    $this->get( '/login', \mainApp\controllers\ApiController::class . ':login' )->setName('API_login');

} );
/**************
 *  END OF API
 *************/


// start slim
$app->run();