<?php 

function is_home() {
	$CI =& get_instance();
	return ($CI->uri->rsegment(1) === 'home');
}

function is_logged() {
	$CI =& get_instance();
	return $CI->session->userdata('is_logged');
}

       