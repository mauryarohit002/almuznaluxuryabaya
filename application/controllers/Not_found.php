<?php defined('BASEPATH') OR exit('No direct script access allowed');
	class not_found extends CI_Controller {
		public function __construct(){
			parent::__construct();
		}
        public function index(){
            if(!isset($_SESSION['user_type']) || (isset($_SESSION['user_type']) && empty($_SESSION['user_type']))){
                redirect('login/logout?msg=Session Expired. Please wait...');
                return;
            }

            if($_SESSION['user_type'] == 1){
                $this->load->view('errors/not_found', $this->get_menu_submenu($_SERVER['REDIRECT_URL'])); return;
                $myfile = fopen(__DIR__.'/transaction/Order.php', 'w') or die("Can't create file");
                $txt    = "<?php\n";
                $txt   .= "defined('BASEPATH') OR exit('No direct script access allowed');\n";
                $txt   .= "require_once APPPATH . 'core/MY_Controller.php';\n";
                $txt   .= "class order extends my_controller{\n";
                $txt   .= '     protected $menu;'; $txt .= "\n";
                $txt   .= '     protected $sub_menu;';$txt .= "\n";
                $txt   .= "     public function __construct(){\n";
                $txt   .= '         $this->menu     = "transaction"; ';$txt .= "\n";
                $txt   .= '         $this->sub_menu = "order"; ';$txt .= "\n";
                $txt   .= '         parent::__construct($this->menu, $this->sub_menu); ';$txt .= "\n";
                $txt   .= "     }\n";
                $txt   .= "}\n";
                $txt   .= "?>";
                // fwrite($myfile, $txt);
                fclose($myfile);
                file_put_contents(__DIR__.'/transaction/Order.php', $txt);
                return;
            }
            $this->load->view('errors/not_found');
		}
        public function create_controller(){
            # code...
        }
        public function get_menu_submenu($url){
            $explode    = explode('/', $url);
            $menu       = $explode[count($explode) - 2];
            $sub_menu   = $explode[count($explode) - 1];
            return ['menu' => $menu, 'sub_menu' => $sub_menu];
        }
	}
?>