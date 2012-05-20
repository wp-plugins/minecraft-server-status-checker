<?php
	/*
	Plugin Name: MinecraftSV Status
	Plugin URI: http://www.minecraftsv.com
	Description: Plugin for displaying Minecraft Server status
	Author: MinecraftDL
	Version: 0.1
	Author URI: http://www.minecraftdl.com
	*/
	
	define('PLUGIN_URL', plugin_dir_url( __FILE__ ));
	
	add_action("widgets_init", array('mcsv_status', 'register'));
	register_activation_hook( __FILE__, array('mcsv_status', 'activate'));
	register_deactivation_hook( __FILE__, array('mcsv_status', 'deactivate'));
	class mcsv_status {
	function activate(){
			$data = array( 'option1' => 'Default value' ,'option2' => 55);
			if ( ! get_option('mcsv_status')){
				add_option('mcsv_status' , $data);
			} else {
			  	update_option('mcsv_status' , $data);
			}
	}
	function deactivate(){
	    	delete_option('mcsv_status');
	}
	function control(){
	    	$data = get_option('mcsv_status');
		?>
			<p><label>Title : <input name="widget_title" type="text" value="<?php echo $data['widget_title']; ?>" /></label></p>
	    	<p><label>IP : <input name="server_ip" type="text" value="<?php echo $data['server_ip']; ?>" /></label></p>
	    	<p><label>Port (25565) : <input name="server_port" size="5"  type="text" value="<?php echo $data['server_port']; ?>" /></label></p>
		<?php
			if (isset($_POST['server_ip'])){
			$data['widget_title'] = attribute_escape($_POST['widget_title']);
			$data['server_ip'] = attribute_escape($_POST['server_ip']);
			$data['server_port'] = attribute_escape($_POST['server_port']);
			update_option('mcsv_status', $data);
		}
	}
	function widget($args){
	  
	  		
	  
	  		wp_enqueue_style('mcsv', PLUGIN_URL.'/css/mcsv_button.css');
	  
	  		$data = get_option('mcsv_status');
	  		$title = $data['widget_title'];
	  		$port = $data['server_port'];
	  		if(empty($port)) {
	  			$port = "25565";
	  		}
	  		
			echo $args['before_widget'];
			if(!empty($title)) {
			echo $args['before_title'] . $title . $args['after_title'];
			}
			// Check Server Status
			
			$online = @fsockopen($data['server_ip'], $port, $errno, $errstr, 1);
			
			echo "<span class='mcsv_status'> IP : ".$data['server_ip']."</span>";
			
			if($online) {
				echo '<a href="#" class="btn btn-success" rel="tooltip" title="first tooltip">Server is Online</a>';
			}else{
				echo '<a href="#" class="btn btn-danger">Server is Offline</a>';
			}
		
			
			// End
			echo $args['after_widget'];
	  }
	  function register(){
			register_sidebar_widget('MinecraftSV Status', array('mcsv_status', 'widget'));
			register_widget_control('MinecraftSV Status', array('mcsv_status', 'control'));
	  }
	}
	
	
?>