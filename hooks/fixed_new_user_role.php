<?php
use Directus\Application\Application;
use Directus\Services;
use Directus\Hook\Payload;

function checkUserValidity(Payload $user){
	if($user->has('email') && strrpos($user->get('email'), "@liceopigafetta.edu.it") === false){
		throw new \Directus\Exception\BadRequestException('Invalid email');
	}

	$phoneRegex = "/^(?:\+(\d{1,3}))([-. (]*(\d{3})[-. )]*)?((\d{3})[-. ]*(\d{2,4})(?:[-.x ]*(\d+))?)$/";
	if($user->has('title') && preg_match($phoneRegex, $user->get('title')) !== 1){
		throw new \Directus\Exception\BadRequestException('Invalid phone number');
	}

	$user->set('role', 3);
	return $user;
}

return [
	'filters' => [
		'item.create.directus_users:before' => function (Payload $data) {
			$app = Application::getInstance();
			$acl = $app->fromContainer('acl');
			//$logger = $app->fromContainer('logger');

			//$logger->debug(print_r($data, true));
			if(!$acl->isAdmin()){
				$data = checkUserValidity($data);
			}
			return $data;
		},
		'item.update.directus_users:before' => function (Payload $data) {
			$app = Application::getInstance();
			//$logger = $app->fromContainer('logger');
			$acl = $app->fromContainer('acl');

			if(!$acl->isAdmin()){
				if($acl->getUserId() != $data->get('id')){
					throw new \Directus\Exception\BadRequestException('An user can only edit themselves');
				}

				$data = checkUserValidity($data);
			}

			return $data;
		},
	]
];
?>
