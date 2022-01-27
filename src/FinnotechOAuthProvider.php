<?php

namespace Soroosh\FinnotechClient;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;
use Soroosh\FinnotechClient\Exceptions\FinnotechIdentityExceptions;

class FinnotechOAuthProvider extends AbstractProvider
{

    const PATH_API_USER = '/api/v4/user';
    const PATH_AUTHORIZE = '/dev/v2/oauth2/authorize';
    const PATH_TOKEN = '/dev/v2/oauth2/token';
    const SCOPE_SEPARATOR = ',';

    /** @var string */
    public $domain = 'https://apibeta.finnotech.ir';

    /**
     * Gitlab constructor.
     */
    public function __construct(array $options = [], array $collaborators = [])
    {
        if (isset($options['domain'])) {
            $this->domain = $options['domain'];
        }
        parent::__construct($options, $collaborators);
    }

    /**
     * Get authorization url to begin OAuth flow.
     */
    public function getBaseAuthorizationUrl(): string
    {
        return $this->domain . self::PATH_AUTHORIZE;
    }

    /**
     * Get access token url to retrieve token.
     */
    public function getBaseAccessTokenUrl(array $params): string
    {
        return $this->domain . self::PATH_TOKEN;
    }

    /**
     * Get provider url to fetch user details.
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return $this->domain . self::PATH_API_USER;
    }

    /**
     * Get the default scopes used by GitLab.
     * Current scopes are 'api', 'read_user', 'openid'.
     *
     * This returns an array with 'api' scope as default.
     */
    protected function getDefaultScopes(): array
    {
        return ["refund:deposit-card:post"];
    }

    /**
     * GitLab uses a space to separate scopes.
     */
    protected function getScopeSeparator(): string
    {
        return self::SCOPE_SEPARATOR;
    }

    /**
     * Check a provider response for errors.
     *
     * @param ResponseInterface $response Parsed response data
     * @throws IdentityProviderException
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if ($response->getStatusCode() >= 400) {
            throw FinnotechIdentityExceptions::clientException($response, $data);
        } elseif (isset($data['error'])) {
            throw FinnotechIdentityExceptions::oauthException($response, $data);
        }
    }

    /**
     * Generate a user object from a successful user details request.
     */
    protected function createResourceOwner(array $response, AccessToken $token): ResourceOwnerInterface
    {
        $user = new GitlabResourceOwner($response, $token);

        return $user->setDomain($this->domain);
    }
}
