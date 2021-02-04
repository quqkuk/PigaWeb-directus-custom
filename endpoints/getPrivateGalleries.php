<?php

return [];

use Directus\Application\Http\Request;
use Directus\Application\Http\Response;
use Directus\Services\ItemsService;

[
	'' => [
		'method' => 'GET',
		'handler' => function (Request $request, Response $response) {
			$itemsService = new ItemsService($this);

			$params = $request->getQueryParams();
			if(!in_array('fields', $params, true))
				$params['fields'] = '*';
			$params['fields'] .= ',users.directus_users_id';
			$items = $itemsService->findAll('private_galleries', $params);
			$uid = $this['auth']->getUser()->getId();

			$temp = [];
			foreach($items['data'] as $el){
				foreach($el['users'] as $allwd){
					if($allwd['directus_users_id'] == $uid){
						$temp[] = $el; 
						unset($temp[count($temp)-1]['users']);
					}
				}
			}
			$items['data'] = $temp;
			return $response->withJson($items);
		}
	]
];

?>
