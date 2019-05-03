<?php
/*
Plugin Name: Weberino Quiz Creator
Plugin URI: https://www.weberino.com/
Description: Easily create a viral quiz for your website with this great quiz creator.
Version: 1.0.0
Author: Weberino
*/
/*
create custom post
create custom fields

create a question table

load vue

shortcode

social share
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Weberino {

	function  __construct() {
		$this->register_actions;
	}

	function register_actions() {

		add_action( 'init', [$this, 'create_post_type'] );
		add_action('add_meta_boxes', [$this, 'add_questions']);
		add_action('save_post', [$this, 'save_postdata']);
		add_action( 'admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
		add_action( 'wp_enqueue_scripts', [$this, 'enqueue_assets'] );

		$this->add_shortcode();
	}

	function create_post_type() {
		register_post_type( 'quiz',
			array(
				'labels' => array(
					'name' => __( 'Quizzes' ),
					'singular_name' => __( 'Quiz' ),
					'add_new' => __( 'Add Quiz' )
				),
				'public' => true,
				'has_archive' => true,
				'show_in_menu' => true
			)
		);
	}

	function enqueue_admin_assets() {
		wp_enqueue_style( 'weberino_admin_css', plugins_url('assets/css/weberino.css' , __FILE__ ));
	}

	function enqueue_assets() {
		wp_enqueue_script( 'vue', plugins_url('assets/js/vue.js' , __FILE__ ), [], null, true);
		wp_enqueue_script( 'vue-weberino', plugins_url('assets/js/weberino.js' , __FILE__ ), 'vue', null, true);
	}

	function add_questions() {
		add_meta_box(
			'weberino_question',
			'Add hints or questions',
			[$this, 'add_questions_html'],
			'quiz'
		);
	}

	function add_shortcode() {
		add_shortcode('weberino_quiz', [$this, 'weberino_load_shortcode']);
	}

	function weberino_load_shortcode() {
		require_once plugin_dir_path( __FILE__ ) . 'templates/quiz.php';
	}

	function add_questions_html($post) {
		require_once plugin_dir_path( __FILE__ ) . 'templates/quiz-table.php';
	}

	function save_postdata($post_id) {
		$x = 1;

		while ($x <= 10) {
			if (array_key_exists('weberino_question_'. $x, $_POST)) {
				$question = sanitize_text_field($_POST['weberino_question_'. $x]);
				update_post_meta(
					$post_id,
					'weberino_question_'. $x,
					$question
				);
			}

			if (array_key_exists('weberino_answer_'. $x, $_POST)) {
				$answer = sanitize_text_field($_POST['weberino_answer_'. $x]);
				update_post_meta(
					$post_id,
					'weberino_answer_'. $x,
					$answer
				);
			}
			$x++;
		}

	}
}

$weberino = new Weberino();
$weberino->register_actions();

function footag_func( $atts ) {
	require_once plugin_dir_path( __FILE__ ) . 'templates/quiz.php';
}
add_shortcode( 'weberino-quiz', 'footag_func' );
