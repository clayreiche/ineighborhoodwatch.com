<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$latlng = str_replace(' ', '', $_REQUEST['latlng']);
		$latlng = str_replace('(', '', $latlng);
		$latlng = str_replace(')', '', $latlng);
		
		$data['googleaddress'] = $this->GetAddress($latlng);
		
		$this->load->view('header');
		$this->load->view('register_vw',$data);
		$this->load->view('footer');
	}
	
	public function GetAddress($latlng)
	{
		$curl_url = "http://maps.googleapis.com/maps/api/geocode/xml?&latlng=" . $latlng . "&sensor=true";
        $curl = curl_init($curl_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
        $curlResponse = curl_exec($curl);
        if (curl_errno($curl)) {
                print curl_error($curl);
        }
        curl_close($curl);
		//// translate the XML feed into array
        $xml = new SimpleXMLElement($curlResponse);
		if($xml->status == 'OK') {
			foreach($xml->result as $result) {
				if($result->type == 'street_address') {
					$formattedaddress = $result->formatted_address;
					foreach($result->address_component as $component) {
						if($component->type[0] == 'street_number') {
							$streetnumber = $component->long_name;
						}elseif($component->type[0] == 'route') {
							$route = $component->long_name;
						}elseif($component->type[0] == 'neighborhood') {
							$neighborhood = $component->long_name;
						}elseif($component->type[0] == 'locality') {
							$city = $component->long_name;
						}elseif($component->type[0] == 'administrative_area_level_2') {
							$county = $component->long_name;
						}elseif($component->type[0] == 'administrative_area_level_1') {
							$state = $component->long_name;
						}elseif($component->type[0] == 'country') {
							$country = $component->long_name;
						}elseif($component->type[0] == 'postal_code') {
							$zip = $component->long_name;
						}
					}
				}
			}
			$return = array('latlng' => $latlng, 'formattedaddress' => $formattedaddress, 'streetnumber' => $streetnumber, 'route' => $route, 'neighborhood' => $neighborhood, 'city' => $city, 'county' => $county, 'state' => $state, 'country' => $country, 'zip' => $zip);
		}		
		return $return;
	}
	
	public function Save($post)
	{
		$return = 'SUCCESS';
		//Format phone numbers
		$housephone = str_replace("(","",$post['housephone']);
		$housephone = str_replace(")","",$housephone);
		$housephone = str_replace(" ","",$housephone);
		$cellphone = str_replace("(","",$post['cellphone']);
		$cellphone = str_replace(")","",$cellphone);
		$cellphone = str_replace(" ","",$cellphone);
		if(isset($post['blockcaptain'])) {
			$blockcaptain = $post['blockcaptain'];
		}else {
			$blockcaptain = '';
		}
		try {
			//Insert User Data
			$userdata = array('username' => $post['email'],
							'password' => $post['password'],
							'firstname' => $post['firstname'],
							'lastname' => $post['lastname'],
							'middlename' => $post['middlename'],
							'gender' => $post['gender'],
							'isblockcaptain' => $blockcaptain,
							'chairman' => $post['chairman']
						);
			$this->db->insert('users', $userdata);
			
			//Insert Alert Data
			$alertdata = array();
			$alertdata[] = array('username' => $post['email'], 'alerttype' => 'email', 'data' => $post['email']);
			if($post['housephone']) {
				$alertdata[] = array('username' => $post['email'], 'alerttype' => 'phonecall', 'data' => $housephone);
			}
			if($post['cellphone']) {
				$alertdata[] = array('username' => $post['email'], 'alerttype' => 'phonecall', 'data' => $cellphone);
				$alertdata[] = array('username' => $post['email'], 'alerttype' => 'text', 'data' => $cellphone);
			}
			//foreach($alertdata as $row) {
			$this->db->insert_batch('useralerttypes', $alertdata);
			//}

			//Insert Address Data
			$addressdata = array('username' => $post['email'],
									'latlng' => $post['latlng'],
									'address1' => $post['streetaddress'],
									'city' => $post['city'],
									'state' => $post['state'],
									'zip' => $post['zip'],
									'verified' => '1',
									'formattedaddress' => $post['formattedaddress'],
									'county' => $post['county'],
									'neighborhood' => $post['neighborhood'],
									'country' => $post['country']
								);
			$this->db->insert('addresses', $addressdata);
			
			//Send a welcome email
			$from = "'watersedge@ineighborhoodwatch.com', 'Waters Edge Neighborhood Watch'";
			$to = $post['email'];
			$subject = "Welcome to the " . $post['neighborhood'] . " Neighborhood Watch!";
			$message = "Dear " . $post['firstname'] . " " . $post['lastname'] . ",\n\nThank you for signing up with IneighborhoodWatch.Com. The site is still under construction and should be accessible within the next month or 2. You are one of our first subscribers and we will let you know as soon as the site is ready!\n\nUsername: " . $post['email'] . "\nPassword: " . $post['password'] . "\n\nPlease keep this information handy. As we build out the site I might call on you to help test some features.\n\nOnce again, thank you for signing up and let's keep our neighborhood safe!\n\nSincerely,\n\nClay Reiche and the " . $post['neighborhood'] . " Neighborhood Watch Commitee";
		}catch (Exception $e) {
			$return = "Caught exception: " . $e->getMessage();
			return $return;
		}
		$email = Register::SendEmail($to,$subject,$message,$from);
		return $return;
	}
	
	public function SendEmail($to,$subject,$message,$from=null,$cc=null,$bcc=null)
	{
		try {
			$this->email->from($from);
			$this->email->to($to); 
			$this->email->cc($cc); 
			$this->email->bcc($bcc); 

			$this->email->subject($subject);
			$this->email->message($message);	

			$this->email->send();
		}catch (Exception $e) {
			return "Exception Caught: " . $e->getMessage();
		}
		return 'SUCCESS';
	}
}