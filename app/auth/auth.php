<?php
	/***
	 * Get user data (attribs) from IdP.
	 *
	 * Author:  Simon Skrødal
	 */

	// NOTE!!! Points to SimpleSAMLphp installation on server (which needs to be configured and registered with Feide)
	require_once('/var/simplesamlphp/lib/_autoload.php');

	$auth = new SchedulatorAuth();

	/**
	 * Class SchedulatorAuth
	 *
	 * NOTE!!! THIS CLASS ALSO MAKES USE OF A DEPRECATED API TO FETCH DETAILS ABOUT MEDIASITE SUBSCRIBERS.
	 * THE CLASS, AS IS, WILL NOT WORK!
	 */

	class SchedulatorAuth {

		public $kindMediasiteID = '123845';
		private $as, $attributes;
		private $feide_name, $feide_login, $feide_firstname, $feide_email, $feide_affiliation, $feide_org;
		private $service_url, $api_path;
		// NOTE: BELOW VARS PERTAINS TO AN API THAT DOES NO LONGER EXIST
		private $org_subscribers, $is_superuser, $subscriber_details;

		public function __construct() {
			$this->as = new SimpleSAML_Auth_Simple('default-sp');
			$this->as->requireAuth();
			$this->attributes = $this->as->getAttributes();

			/* Test superuser */
			/*
			$feide_org = "uninett.no";
			$feide_email = "simon.skrodal@uninett.no";
			$feide_login = "simon@uninett.no";
			$feide_name = "Simon Skrødal";
			$feide_firstname = "Simon";
			*/

			/* Test non-superuser */
			/*
			$feide_org = "ntnu.no";
			$feide_email = "bor.borson@ntnu.no";
			$feide_login = "bb@ntnu.no";
			$feide_name = "Bør Børson Jr.";
			$feide_firstname = "Bør";
			*/

			// Subscribers list from KIND, false if not subscriber
			$this->org_subscribers = $this->isOrgSubscriber($this->feide_login(), $this->feide_org());
			// Used to restrict access to more sensitive user-information
			$this->is_superuser = $this->isSuperUser($this->feide_email(), $this->subscriber_details());
		}

		/**
		 * Use KIND API to check if user's home org subscribes to the AC service.
		 *
		 * NOTE: DEPRECATED - THIS API DOES NO LONGER EXIST!
		 *
		 * @author Simon Skrodal
		 * @since  04.03.2013
		 */
		private function isOrgSubscriber($feide_login, $feide_org) {
			$this->subscriber_details = false;
			if(!isset($feide_login) || !isset($feide_org)) {
				return false;
			}

			$orgSubscribers = json_decode(file_get_contents('NO LONGER EXISTS!!!!!!' . $this->kindMediasiteID));
			// Search for institution in array
			foreach($orgSubscribers->orgSubscribers as $key => $value) {
				// We have a matching institution
				if(mb_strtolower($key) === $feide_org) {
					$this->subscriber_details = $value;
					$this->service_url        = $value->service_uri;
					return $orgSubscribers;
				}
			}

			return false;
		}

		public function feide_login() {
			$this->feide_login = mb_strtolower($this->attributes['eduPersonPrincipalName'][0]);

			return $this->feide_login;
		}

		public function feide_org() {
			//
			$this->feide_org = explode('@', $this->feide_login());
			$this->feide_org = mb_strtolower($this->feide_org[1]);

			return $this->feide_org;
		}

		/**
		 * Is user UNINETT employee or "teknisk ansvarlig" with priority == 1 in Kind?
		 *
		 * Used to restrict access to some information.
		 *
		 * @param $feide_email
		 * @param $subscriber_details
		 *
		 * @return bool
		 * @internal param $org_subscribers
		 *
		 */
		private function isSuperUser($feide_email, $subscriber_details) {

			if($subscriber_details == false) {
				return false;
			}
			// UNINETT employees super users by default
			if(strtolower(end(explode('@', $feide_email))) == 'uninett.no') {
				return true;
			}
			// If "teknisk kontakt" with priority == 1 in Kind
			if(@strtolower($subscriber_details->contact_person->e_post) == strtolower($feide_email)) {
				return true;
			}

			//
			return false;
		}

		public function feide_email() {
			// NO guarantee that mail is set
			$this->feide_email = mb_strtolower($this->attributes['mail'][0]);

			return $this->feide_email;
		}

		public function subscriber_details() {
			return $this->subscriber_details;
		}

		public function logout() {
			$this->as->logout($_SESSION['app_url'] . 'logged_out.php');
		}

		public function feide_affiliation() {
			// For checking "employee" status (make lower case!)
			$this->feide_affiliation = array_map('strtolower', $this->attributes['eduPersonAffiliation']);

			return $this->feide_affiliation;
		}

		public function feide_firstname() {
			if(isset($this->attributes['givenName'][0])) {
				$this->feide_firstname = $this->attributes['givenName'][0];
			} else {
				$this->feide_firstname = $this->feide_name();
			}

			return $this->feide_firstname;
		}

		public function feide_name() {
			// Get the user's full name. Not reliable to trust a single attribute only, so try a few if data is missing.
			if(isset($this->attributes['displayName'][0])) {
				$this->feide_name = $this->attributes['displayName'][0];
				// echo "displayName set!!!";
			} else if(isset($this->attributes['givenName'][0]) && isset($this->attributes['sn'][0])) {
				$this->feide_name = $this->attributes['givenName'][0] . ' ' . $this->attributes['sn'][0];
				// echo "givenName/sn set!!!";
			} else if(isset($this->attributes['cn'][0])) {
				$this->feide_name = $this->attributes['cn'][0];
				// echo "cn set!!!";
			} // FALLBACK IF NO NAME IS ACCESSIBLE FROM ANY OF THE ABOVE ATTRIBS...
			else {
				$this->feide_name = $this->attributes['eduPersonPrincipalName'][0];
			}

			return $this->feide_name;
		}

		public function isAuthenticated() {
			return $this->as->isAuthenticated();
		}

		public function org_subscribers() {
			return $this->org_subscribers;
		}

		public function is_org_subscriber() {
			return $this->org_subscribers !== false;
		}

		public function is_superuser() {
			return $this->is_superuser;
		}

		public function api_url() {
			return $this->service_url() . "/mediasite/api/v1/";
		}

		public function service_url() {
			return $this->service_url;
		}
	}
