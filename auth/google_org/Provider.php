<?php
namespace Directus\Authentication\Sso\Provider\google_org;

use Directus\Authentication\Sso\TwoSocialProvider;
use League\OAuth2\Client\Provider\Google;

class Provider extends TwoSocialProvider
{
	/**
	* @var Google
	*/
	protected $provider = null;

	/**
	* @inheritdoc
	*/
	public function getScopes()
	{
		return [
			'email'
		];
	}

	/**
	* Creates the Google provider oAuth client
	*
	* @return Google
	*/
	protected function createProvider()
	{
		$this->provider = new Google_Org([
			'clientId'          => $this->config->get('client_id'),
			'clientSecret'      => $this->config->get('client_secret'),
			'redirectUri'       => $this->getRedirectUrl(),
			'hostedDomain'      => $this->config->get('hosted_domain'),
			'useOidcMode'       => (bool) $this->config->get('use_oidc_mode'),
		]);

		return $this->provider;
	}
}

class Google_Org extends Google {
	protected function checkResponse($response, $data){
		parent::checkResponse($response, $data);
	}
}
