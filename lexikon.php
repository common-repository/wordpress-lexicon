<?php
/*
Plugin Name: Wordpress Lexikon
Plugin URI: http://www.3task.de/tools-programme/wordpress-lexikon/
Description: ACHTUNG: Sicherheitsluecke im Plugin!!!! Diese Lexikon Version wird nicht mehr weiter entwickelt!!! Die neuste Version mit Sicherheitsupdates und neuen Funktionen findet Ihr jetzt ausschliesslich auf www.3task.de unter Tools.
Version: 1.3
Author: Frank Stemmler
Author URI: http://www.3task.de

*/


class lexikon_int {
	
	function Enable() {
		add_action('admin_menu', array('lexikon_int', 'RegisterAdminPage'));
		add_filter('the_content', array('lexikon_int', 'ContentFilter'));
		add_filter('wp_footer', array('lexikon_int', 'RegisterFooter'));
		$GLOBALS["LexikonPlugin"] = get_option('LexikonPlugin');
	}

	function Install() {
		add_option('LexikonPlugin', "");
	}

	function RegisterAdminPage() {
		add_submenu_page('options-general.php','Lexikon','Lexikon',8,__FILE__,'LexikonAdminPage');
	}


	function RegisterFooter() {
		echo '<a href="http://www.3task.de" title="Webdesign">Webdesign</a>';
	}


	function ContentFilter($content) {

		global $post;
		
		if ($post->ID == $GLOBALS["LexikonPlugin"]) {
			
		
			
			if ($GetAlphabeticList)
			{
				$output = null;
				
				$output = "<div class='AlphabeticList'>";
				

					
				$output .= "</div>";
			
				foreach ($GetAlphabeticList as $initial => $group) {	
				
					if ( $group ) 
						$output .= "<h2 class='initial'>".$initial."</h2><a name='".$initial."' />";
					 			   
				    for ($i = 0, $x = count($group); $i < $x; ++$i) 
						$output .= "<a href='".$group[$i]['post_url']."'>".$group[$i]['post_title']."</a><br />";	
				   
				}	
			}		
			
			$content .= $output;
			
		} else {

			$results = lexikon_int::getResults();	
			
			if ($results){
				foreach($results as $result)
					$content = preg_replace('/'.ent2ncr(htmlentities(utf8_decode($result['post_title']))).'/', '<a href="'.get_permalink($result['ID']).'" title="Lexikoneintrag: '.$result['post_title'].'">'.$result['post_title'].'</a>', $content, 1);
			}
		}
		
		return $content;		
		


	function getInitial($string) {
		
		$string = utf8_decode($string);
		
	    $initial = $string{0};
	    if (preg_match('/^[a-z]$/i', $initial)) 
	        return strtoupper($initial);
	  
	    switch ($initial) {
	    	
	        case 'ä': case 'Ä':
	            return 'A';
	        case 'ö': case 'Ö':
	            return 'O';
	        case 'ü': case 'Ü':
	            return 'U';
	        default:
	            return '#';
	    }
	}

}



function LexikonAdminPage() {
	
	?><div class="wrap">
		<div id="icon-tools" class="icon32"><br></div>
		<h2>Lexikon</h2>

		<?php 
			if ( isset($_POST['submit']) ) { 
				update_option('LexikonPlugin',(int)$_POST['LexikonPlugin']); 
			} 
		?> 	
		
		<div style="padding: 20px; background:#fff; border: 1px solid #ccc; -moz-border-radius: 5px; margin: 15px 0 0 0;">
		<form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">

			<p>
				Lexikon auf Seiten-ID <input name="LexikonPlugin" type="text" value="<?php echo get_option('LexikonPlugin'); ?>" style="width: 50px;" />  aktivieren.
			</p>

			<hr style="border:none; background:none; border-top: 1px solid #ccc; " />
			
			<p class="submit" style="padding: 5px 0 0 0;">
				<input name="submit" class="button-primary" value="Änderungen speichern" type="submit">
			</p>
		</form>
		</div>
	</div><?php

}


if(defined('ABSPATH') && defined('WPINC')) 
	add_action("init",array("lexikon_int","Enable"),1000,0);

register_activation_hook( __FILE__, array('lexikon_int', 'Install'));


?>