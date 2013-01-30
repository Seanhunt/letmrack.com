<?php
function wpnj_one_two($atts, $content = null) {
		return '<div class="col c-1-2">' . do_shortcode($content) . '</div>';
}
add_shortcode('one_two', 'wpnj_one_two');

function wpnj_two_two($atts, $content = null) {
		return '<div class="col c-2-2 lst">' . do_shortcode($content) . '</div>';
}
add_shortcode('two_two', 'wpnj_two_two');


function wpnj_one_three($atts, $content = null) {
		return '<div class="col c-1-3">' . do_shortcode($content) . '</div>';
}
add_shortcode('one_three', 'wpnj_one_three');

function wpnj_two_three($atts, $content = null) {
		return '<div class="col c-2-3">' . do_shortcode($content) . '</div>';
}
add_shortcode('two_three', 'wpnj_two_three');

function wpnj_three_three($atts, $content = null) {
		return '<div class="col c-3-3 lst">' . do_shortcode($content) . '</div>';
}
add_shortcode('three_three', 'wpnj_three_three');


function wpnj_one_four($atts, $content = null) {
		return '<div class="col c-1-4">' . do_shortcode($content) . '</div>';
}
add_shortcode('one_four', 'wpnj_one_four');

function wpnj_two_four($atts, $content = null) {
		return '<div class="col c-2-4">' . do_shortcode($content) . '</div>';
}
add_shortcode('two_four', 'wpnj_two_four');

function wpnj_three_four($atts, $content = null) {
		return '<div class="col c-3-4">' . do_shortcode($content) . '</div>';
}
add_shortcode('three_four', 'wpnj_three_four');

function wpnj_four_four($atts, $content = null) {
		return '<div class="col c-4-4 lst">' . do_shortcode($content) . '</div>';
}
add_shortcode('four_four', 'wpnj_four_four');


function wpnj_three_one($atts, $content = null) {
		return '<div class="col c-3-1">' . do_shortcode($content) . '</div>';
}
add_shortcode('three_one', 'wpnj_three_one');

function wpnj_three_two($atts, $content = null) {
		return '<div class="col c-3-2 lst">' . do_shortcode($content) . '</div>';
}
add_shortcode('three_two', 'wpnj_three_two');