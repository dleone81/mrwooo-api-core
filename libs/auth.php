<?php
	use \Firebase\JWT\JWT;

	class MRWOOO_API_LIBS_Auth {
		/*
		*	This method check is user exists
		*	then create and return JWT token
		*	$data array
		*
		*	JSON sample
			{
				"user": "your.email@address.com",
				"password": "yourpassword"
			}
		*/
		public static function user($data) {
			$user = $data['user'];
			$password = $data['password'];
			$auth = wp_authenticate($user, $password);

			$event =	$_SERVER['REQUEST_URI'];							
			$remote	=	$_SERVER['REMOTE_ADDR'];

			if($auth->data){
				$id = $auth->data->ID;
				$email = $auth->data->user_email;	

				$token = array(
					'id' => $id,
					'email'	=> $email
				);
				$jwt = JWT::encode($token, MRWOOOJWTKEY);

				MRWOOO_DB_Logger::create($id, $event, '200', $remote);
				return $jwt;
			}
			if($auth->errors){
				$message = 'Authentication failed';

				// log
				MRWOOO_DB_Logger::create(0, $event, '401', $remote);
				return new WP_Error( 'Unhautorized', $message, array( 'status' => 401 ) );
			}
		}
		/*
		* This method can be use to filter blacklisted IP
		* in the future :)
		*/
		/*public static function allowedIps() {
			$remote = $_SERVER['REMOTE_ADDR'];
			$allowed = unserialize(ALLOWED_IPs);
			if(in_array($remote, $allowed)) {
				return true;
			}
		}*/
		/*
		* Thismethod create a provider token
		*/
		public static function CreateJWTToken($provider){

			// get providerId
			$providerId = Provider::Get($provider);
			if($providerId != NULL) {
				
				// create token
				$token = array(
					'ProviderId' => $providerId,
					'Provider' => $provider,
					'Expires' => (strtotime('NOW') + (3*60*60)) // expires in an hour
				);

				// create JWT token, #ref: https://github.com/firebase/php-jwt
				$jwt_token = JWT::encode($token, JWT_KEY);

				// return token
				return $jwt_token;
			} else {
				return new Tonic\Response(Tonic\Response::UNAUTHORIZED);
			}
		}
		/*
		* Thismethod validate a provider token
		*/
		// validate JWT token
		public static function ValidateJWTToken(){

			$auth = isset($_SERVER['HTTP_X_AUTH']) ? $_SERVER['HTTP_X_AUTH'] : '';
			// SL: override http_x_auth via session variable
			
			session_start();
			if($auth=='' && isset($_SESSION['jwt_token']))
				$auth='Bearer '.$_SESSION['jwt_token'];
			session_commit();
			// locate token
			if(strpos($auth, 'Bearer') !== false){

				$jwt = str_replace('Bearer ', '', $auth);

				try{
					// decode token
					$jwt_decoded = JWT::decode($jwt, JWT_KEY, array('HS256'));
					
					if($jwt_decoded != NULL){

						// check to make sure the token has not expired
						if(strtotime('NOW') < $jwt_decoded->Expires){
							return $jwt_decoded;
						}
						else{
							return NULL;
						}

					}
					else{
						return NULL;
					}

					// return token
					return $jwt_decoded;

				} catch(Exception $e){
					return NULL;
				}

			}
			else{
				return NULL;
			}
		}
	}
?>