<?php
namespace FriendlySold\Controller;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use FriendlySold\Domain\User;

class ApiControllerDelete {

	
	/**
	* API Delete details controller.
	*
	* @param integer $id Article id
	* @param Application $app Silex application
	*
	* @return Article details in JSON format
	*/
	public function deleteUser($id, Application $app){
		try {
			$app['UserDAO']->delete($id);
		}
		catch(\Exception $e) {

			return $app->json(array(
				'status' => 'KO',
				'error' => $e->getMessage()
			), 200);
		}
		return $app->json(array(
			'status' => 'OK'
		), 200);
	}


	public function deleteGroup($id, Application $app) {

		try {
			$app['GroupDAO']->delete($id);
		}
		catch(\Exception $e) {

			return $app->json(array(
				'status' => 'KO',
				'error' => $e->getMessage()
			), 200);

		}
		return $app->json(array(
			'status' => 'OK'
		), 200);
	}
	
	public function deleteMoney($id, Application $app) {
		try {
			$app['MoneyDAO']->delete($id);
		}
		catch(\Exception $e) {

			return $app->json(array(
				'status' => 'KO',
				'error' => $e->getMessage()
			), 200);
		}		
		return $app->json(array(
			'status' => 'OK'
		), 200);
	}
	
}