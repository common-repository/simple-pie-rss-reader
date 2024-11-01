<?php
/* Plugin Name:Simple Pie Rss Reader
 * Plugin URI: http://davidmregister.com/simplepie-rss-reader/
 * Description: Using the power of Simple Pie to parse RSS feeds ex. [parse_feed feed="http://example.com/feed" num="8"(optional) name="Example Title"(optional) url="http://example.com/"(optional) ]
 * Author: David Register
 * Version: 1.4.1
 * Author URI: http://www.davidmregister.com/
 */

include(dirname(__FILE__).'/simplepie.inc');

function get_feed($atts, $content = null){
	
	extract(shortcode_atts(array(
		"feed" => 'http://',
		"num" => '1',
		"name" => '',
		"url" => 'http://',
		//"showDesc" => 'true',
	), $atts));
	
	// Parse it
	$feed = new SimplePie();
	
	$feed->set_feed_url($atts['feed']);
	$feed->enable_cache(true);
	$feed->set_cache_location(dirname(__FILE__) . '/cache');
	$feed->set_cache_duration(3600);
	$feed->init();
	
	$feed->handle_content_type();
	
        $html = '<div id="'.str_replace(' ', '', strtolower( $feed->get_title() ) ).'" class="sp_results">';
        if ($feed->data):
            $items = $feed->get_items(0,$atts['num']);
            $html .= '<div class="source-image" id="'.str_replace(' ', '', strtolower( $feed->get_title() ) ) . '-logo"></div>';
            $html .= '<h2><a href="'.(($atts['url'])?$atts['url']:$feed->get_permalink()).'">'.$feed->get_title().'</a></h2>';
            foreach($items as $item):
                $html .= '<div class="chunk" style="padding:0 5px;margin-bottom: 8px;">';
                    $html .= '<h4><a href="'.$item->get_permalink().'" target="_blank">'.$item->get_title().'</a></h4>';
                    $html .= '<h6>'.$item->get_date('M j Y').'</h6>';
                    //if($showDesc == 'false'){
                    	$html .= $item->get_content();
                	//}
                	if ($enclosure = $item->get_enclosure(0)){
-						$html .= '<p><a href="' . $enclosure->get_link() . '" class="download" target="_blank">'.$enclosure->get_title().'</a></p>';
					}
                $html .= '</div>';
            endforeach;  
        endif;
        $html .= '</div>';
        
        return $html;
} 

add_shortcode('parse_feed', 'get_feed');

?>