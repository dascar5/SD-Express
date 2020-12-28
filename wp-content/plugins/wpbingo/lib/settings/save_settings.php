<?php
	add_action('redux/options/dimita_settings/saved', 'dimita_save_theme_settings', 10, 2);
	use Leafo\ScssPhp\Compiler;
	use Leafo\ScssPhp\Server;	
	function dimita_save_theme_settings() {
		global $dimita_settings,$skins;
		$reduxdimitaSettings = new Redux_Framework_dimita_settings();
		$reduxFramework = $reduxdimitaSettings->ReduxFramework;
		if (isset($dimita_settings['compile-css']) && $dimita_settings['compile-css']) {
			require_once( dirname(__FILE__) . '/scssphp/scss.inc.php');			
			ob_start();
            $sassDir = get_template_directory().'/sass/';
            $cssDir = get_template_directory().'/css/';
            $variables = '';
            if (is_writable($sassDir) == false){
                @chmod($sassDir, 0755);
            }
            $scss = new Compiler();
            $scss->addImportPath($sassDir);
			$variables = '$theme-color: '.$dimita_settings['main_theme_color'].';';
			$string_sass = $variables . file_get_contents($sassDir . "template.scss");
			$string_css = $scss->compile($string_sass);
			file_put_contents($cssDir . 'template.css', $string_css);			
		}	
	}
?>