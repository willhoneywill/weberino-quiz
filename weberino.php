<?php
/*
Plugin Name: Weberino Timed Quiz Creator
Plugin URI: https://www.weberino.com/
Description: Easily create a timed viral quiz for your website with this great quiz creator.
Version: 1.0.0
Author: Weberino
*/


if( ! defined( 'ABSPATH' ) ) exit;

require_once plugin_dir_path( __FILE__ ) . 'admin/edit-quiz.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/helpers.php';
require_once plugin_dir_path( __FILE__ ) . 'public/load-quiz.php';


class Weberino {

	function  __construct() {
		$this->register_actions();
	}

	function register_actions() {

		add_action( 'init', [$this, 'create_post_type'] );

		$this->add_shortcode();
	}

	function create_post_type() {
		register_post_type( 'weberino-quiz',
			array(
				'labels' => array(
					'name' => __( 'Quizzes' ),
					'singular_name' => __( 'Quiz' ),
					'add_new' => __( 'Add Quiz' ),
					'add_new_item' => __( 'Add Quiz' ),
					'edit_item' => __( 'Edit Quiz' ),
					'item_published' => __( 'Quiz is ready to use' ),
				),
				'public' => true,
				'has_archive' => true,
				'show_in_menu' => true,
				'supports' => ['title']
			)
		);
	}


	function add_shortcode() {
		add_shortcode('weberino_quiz', [$this, 'weberino_load_shortcode']);
	}

	function weberino_load_shortcode() {
		require_once plugin_dir_path( __FILE__ ) . 'templates/quiz.php';
	}

}

add_action( 'wp_ajax_load_question',  'load_question' );
add_action( 'wp_ajax_nopriv_load_question', 'load_question' );

$weberino = new Weberino();

if (is_admin()) {
	$weberino_admin = new Weberino_edit_quiz();
}

$weberino_load = new Weberino_load_quiz();
$weberino_load->register_ajax_actions();
