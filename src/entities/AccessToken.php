<?php
/**
 * File "AccessToken.php"
 * @author Thomas Bourrely
 * 18/07/2017
 */

namespace mainApp\entities;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;

/**
 * Class AccessToken
 *
 * @package server\entities
 */
class AccessToken implements AccessTokenEntityInterface
{
    /**
     * access token model
     *
     * @var
     */
    private $token;

    /**
     * AccessToken constructor.
     *
     * @param $token_model
     */
    public function __construct( $token_model )
    {
        $this->token = $token_model;
    }

    /**
     * Convert token to JWT
     *
     * @param CryptKey $privateKey
     * @return \Lcobucci\JWT\Token
     */
    public function convertToJWT(CryptKey $privateKey)
    {
        return (new Builder())
            ->setAudience($this->getClient()->getIdentifier())
            ->setId($this->getIdentifier(), true)
            ->setIssuedAt(time())
            ->setNotBefore(time())
            ->setExpiration($this->getExpiryDateTime()->getTimestamp())
            ->setSubject($this->getUserIdentifier())
            ->set('scopes', $this->getScopes())
            ->sign(new Sha256(), new Key($privateKey->getKeyPath(), $privateKey->getPassPhrase()))
            ->getToken();
    }

    /**
     * Return the identifier
     *
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->token->access_token;
    }

    /**
     * Set the identifier
     *
     * @param $identifier
     */
    public function setIdentifier( $identifier )
    {

    }

    /**
     * Get token's expire time
     *
     * @return \DateTime
     */
    public function getExpiryDateTime()
    {
        return new \DateTime( $this->token->expire_date );
    }

    /**
     * Set token expire time
     *
     * @param \DateTime $dateTime
     */
    public function setExpiryDateTime(\DateTime $dateTime)
    {

    }

    /**
     * Set user identifier
     *
     * @param int|string $identifier
     */
    public function setUserIdentifier($identifier)
    {

    }

    /**
     * Get user identifier
     *
     * @return mixed
     */
    public function getUserIdentifier()
    {
        return $this->token->user_id;
    }

    /**
     * Return client associated to the token
     *
     * @return \server\entities\Client
     */
    public function getClient()
    {

    }

    /**
     * Set teh client associated to the token
     *
     * @param ClientEntityInterface $client
     */
    public function setClient(ClientEntityInterface $client)
    {

    }

    /**
     * Add a scope
     *
     * @param ScopeEntityInterface $scope
     */
    public function addScope(ScopeEntityInterface $scope)
    {

    }

    /**
     * Return the list of the token scopes
     *
     * @return array
     */
    public function getScopes()
    {

    }
}