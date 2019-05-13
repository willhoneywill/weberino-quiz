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
		wp_enqueue_style( 'weberino_admin_css', plugins_url('assets/css/weberino-admin.css' , __FILE__ ));
	}

	function enqueue_assets() {
		wp_enqueue_style( 'weberino_css', plugins_url('assets/css/weberino.css' , __FILE__ ));

		wp_enqueue_script( 'vue', plugins_url('assets/js/vue.js' , __FILE__ ), [], null, true);
		wp_enqueue_script('axios', 'https://unpkg.com/axios/dist/axios.min.js', ['vue'], '2.5.17', true);

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

		$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

		update_post_meta(
			$post_id,
			'weberino_question',
			$_POST['weberino_question']
		);

		update_post_meta(
			$post_id,
			'weberino_answer',
			$_POST['weberino_answer']
		);

	}
}

$weberino = new Weberino();
$weberino->register_actions();

function footag_func( $atts ) {

	require_once plugin_dir_path( __FILE__ ) . 'templates/quiz.php';
}
add_shortcode( 'weberino-quiz', 'footag_func' );

add_action( 'wp_ajax_load_questions', 'load_questions' );
add_action( 'wp_ajax_nopriv_load_questions', 'load_questions' );

function load_questions() {
	$id = (int) $_POST['id'];
	$questions = [];
	$weberino_question = get_post_meta($id, 'weberino_question');

	foreach($weberino_question[0] as $key => $val) {
		$questions[$key]->question = $val;
		$questions[$key]->id = $key;
	}

	echo json_encode($questions);
	die();


}

add_action( 'wp_ajax_check_answer', 'check_answer' );
add_action( 'wp_ajax_nopriv_check_answer', 'check_answer' );

function check_answer() {
	$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
	$response= [];
	$id = (int) $_POST['id'];
	$answer = $_POST['answer'];
	$answers = get_post_meta($id, 'weberino_answer');

	foreach($answers[0] as $key => $val) {
		if($answer == $val) {
			$response['response']->answer = $val;
			$response['response']->key = $key;
			$response['response']->correct = true;

			echo json_encode( $response);
			die();
		}
	}

	$response['response']->correct = false;

	echo json_encode( $response);
	die();


}