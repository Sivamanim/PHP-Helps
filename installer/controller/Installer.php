<?php
ini_set("display_errors", "0");
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
class Installer extends CI_Controller
{
	function __construct()
	{	
		parent::__construct();
		if (!$this->check_already_installed())
		{
		}
	}

	public function index()
        {			 
				$this->session->set_userdata(array('step1_completed'=>'true'));		
                $this->load->view('installer');
        }
        
        public function move_next()
        {	 
		  
					$this->session->set_userdata(array('step2_completed'=>'true'));
					
					redirect(base_url() . 'installer/?step=2');			 		
        }
        
	public function check_already_installed()
	{
		include APPPATH . 'config/database.php';
		
		$hostname = $db['default']['hostname'];
		$db_username = $db['default']['username'];
		$db_password = $db['default']['password'];
		$db_name = $db['default']['database'];
		$status = $this->connection_check($hostname, $db_username, $db_password);
		return $status;
	}

	public function connection_check($hostname = '', $db_username = '', $db_password = '')
	{
		if ($hostname=='') {
				$hostname = 'localhost';
			}	
		$connection = mysqli_connect($hostname, $db_username, $db_password);
		if (!$connection)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	public function db_installation()
	{
		$hostname = $_POST['hostname'];
		$db_username = $_POST['db_username'];
		$db_password = $_POST['db_password'];

		$status = $this->connection_check($hostname, $db_username, $db_password);
		if (!$status)
		{
			$this->session->set_userdata(array(
				'error_message' => "Database Connection could not be established , Please check your inputs ",
				"hostname" => $_POST['hostname'],
				"db_username" => $_POST['db_username'],
				"db_password" => $_POST['db_password'],
				"db_name" => $_POST['db_name']
			));
			redirect(base_url() . 'installer/?step=2', $this->data);
		}
		else
		{
			$this->session->set_userdata(array('step3_completed'=>'true'));
			$this->session->set_userdata(array(
				"hostname" => $_POST['hostname'],
				"db_username" => $_POST['db_username'],
				"db_password" => $_POST['db_password'],
				"db_name" => $_POST['db_name']
			));
			$hostname = $this->session->userdata("hostname");
			$db_username = $this->session->userdata("db_username");
			$db_password = $this->session->userdata("db_password");
			$database_name = $this->session->userdata("db_name");
			$dbdata = file_get_contents('./application/config/database.php');
			$dbdata = str_replace('%DB_DATABASE_NAME%', '', $dbdata);
			$dbdata = str_replace('%DB_USERNAME%', trim($db_username) , $dbdata);
			$dbdata = str_replace('%DB_PASSWORD%', trim($db_password) , $dbdata);
			$dbdata = str_replace('%HOSTNAME%', trim($hostname) , $dbdata);
			if (write_file(FCPATH . '/application/config/database.php', $dbdata))
			{

				$this->load->database();
				$this->load->dbutil();
				usleep(1000000);
				if (!$this->dbutil->database_exists($database_name))
				{ 
					$this->create_dbforge_database($database_name);

				}
				else
				{
					$this->load->dbforge();
					$this->dbforge->drop_database($database_name);
					$this->create_dbforge_database($database_name);
				}				
			}
			else
			{
				             	 $this->session->set_userdata(array(
                                    'error_message' => " Database files is not writable "));		
                                                         redirect('installer/?step=2');
			}
		}
		}

		// return $status;

	public function create_dbforge_database($database_name)
	{
		$this->load->dbforge();
		if ($this->dbforge->create_database($database_name, TRUE))
		{
			$dbdata = file_get_contents('./application/config/database.php');
			$dbdata = str_replace('"database"=>""', '"database" =>"' . $database_name . '"', $dbdata);
			if (write_file(FCPATH . '/application/config/database.php', $dbdata))
			{
				$this->load->database();
				$this->load->dbutil();
				usleep(1000000);
				if ($this->dbutil->database_exists($database_name))
				{
					if($this->create_tables())
                                        {
                                            $routesData = file_get_contents('./application/config/routes.php');
                                            $routesData = str_replace('installer','admin_dashboard',$routesData);
                                            if (write_file(FCPATH . '/application/config/routes.php',$routesData))
                                            {
												$this->session->set_userdata(array('step3_completed'=>'true'));
                                                redirect('installer/?step=3');
                                            }
                                            else 
                                            {
												$this->session->set_userdata(array('step2_completed'=>'true'));
                                                  	 $this->session->set_userdata(array(
                                    'error_message' => " Routes files is not writable "));		
                                                         redirect('installer/?step=3');
                                            }
                                        }
                                        else 
                                        {
                                            redirect('installer/?step=3');
                                        }
				}
				else
				{
					 $this->session->set_userdata(array(
				'error_message' => "Database cannot be Created , Please check your inputs "));
				redirect('installer/?step=3');
				}				 
			}
		}
                else
                {
					$this->load->dbforge();
					$this->dbforge->delete_database($database_name, TRUE);
					if(!$this->dbforge->create_database($database_name, TRUE))
					{
							$this->session->set_userdata(array(
						'error_message' => "Database cannot be Created , Please contact Authour. "));				
						redirect('installer/?step=3');
					}
                }
	}

	public function create_tables()
	{
		$hostname = $this->session->userdata("hostname");
		$db_username = $this->session->userdata("db_username");
		$db_password = $this->session->userdata("db_password");
		$database_name = $this->session->userdata("db_name");
		$mysqli = new mysqli($hostname, $db_username, $db_password, $database_name); 
		if (mysqli_connect_errno())
		{
                        $this->session->set_userdata(array(
				'error_message' => "Error with Databsae Connection"));				
			return false;
		}
                if(is_file(getcwd()."/assets/temp_files/install.sql"))
                {
                    $query = file_get_contents('assets/temp_files/install.sql');
                    $mysqli->multi_query($query);
                    $mysqli->close();
                    return true;
                }
                else 
                {
                    $this->session->set_userdata(array(
				'error_message' => "Installer File Not Found "));				
                    return false;
                }
	}
        
        public function admin_details()
        {

            $status = 2 ;
            $this->load->database();
            $admin['email']     = $this->input->post('admin_email');
            $admin['password']  = md5($this->input->post('admin_password'));
            $admin['username']  = $this->input->post('admin_username');    
            if($this->db->insert('administrator',$admin))
            { 
            	   $status = 0;
            }
          
            if($status==0)
            {
                redirect(base_url().'admin_dashboard');
            }
            else
            {
                $this->session->set_userdata(array(
				'error_message' => " Problem While Inserting Data "));	
                redirect('installer/?step=4');
            }
        }
}
