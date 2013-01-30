<?php
/**
 * Download-Button Shortcode in HTML-Code umwandeln.
 * [dl url="" title="" desc="" align="" target=""]
 *
 * @param array $atts
 * @since Grey Opaque 1.0.0
 */
if(!function_exists('greyopaque_sc_download_button')) {
	function greyopaque_sc_download_button($atts) {
		extract(shortcode_atts(array(
			"url" => '',
			"title" => '',
			"desc" => '',
			"align" => '',
			"target" => ''
		), $atts));

		if ($align == '') {
			$align = 'center';
		}

		if($target) {
			$target = 'target="' . $target . '"';
		} else {
			$target = '';
		}

		/**
		 * Auszugebendes HTML erstellen
		 */
		$var_sHTML = '';
		$var_sHTML .= '<div id="downloadbutton" class="dlbutton-align' . $align . '">
							<a href="' . $url . '" ' . $target . '>
								<span>' . $title . '</span>
								<em>' . $desc . '</em>
							</a>
						</div>';

		/**
		 * Nur wenn gefloatet wird, einen Clearer einbauen
		 */
		if ($align == 'right' || $align == 'left') {
			$var_sHTML .= '<div class="dlbutton-floatreset"></div>';
		}

		return $var_sHTML;
	}

	/**
	 * Shortcode zu Wordpress hinzufügen
	 */
	add_shortcode('dl', 'greyopaque_sc_download_button');
}
?>