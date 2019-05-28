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
					'add_new' => __( 'Add Quiz' ),
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

		add_meta_box(
			'weberino_how_to',
			'How to text',
			[$this, 'how_to_html'],
			'quiz'
		);

		add_meta_box(
			'weberino_feedback',
			'Add feedback depending on user\'s score',
			[$this, 'feedback_html'],
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

	function how_to_html($post) {
		require_once plugin_dir_path( __FILE__ ) . 'templates/how-to-box.php';
	}

	function feedback_html($post) {
		require_once plugin_dir_path( __FILE__ ) . 'templates/feedback-box.php';
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

		update_post_meta(
			$post_id,
			'weberino_acceptable_answer',
			$_POST['weberino_acceptable_answer']
		);

		update_post_meta(
			$post_id,
			'weberino_feedback',
			$_POST['weberino_feedback']
		);

		update_post_meta(
			$post_id,
			'weberino_how_to',
			$_POST['weberino_how_to']
		);

	}
}

$weberino = new Weberino();
$weberino->register_actions();

function weberino_load_quiz( $atts ) {
	$title = get_the_title($atts['id']);
	$how_to_play = get_post_meta($atts['id'], 'weberino_how_to', true);
	global $post;
	$current_url = get_permalink($post->ID);

	require_once plugin_dir_path( __FILE__ ) . 'templates/quiz.php';
}
add_shortcode( 'weberino-quiz', 'weberino_load_quiz' );

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

add_action( 'wp_ajax_load_answers', 'load_answers' );
add_action( 'wp_ajax_nopriv_load_answers', 'load_answers' );

function load_answers() {
	$id = (int) $_POST['id'];
	$answers = [];
	$weberino_answer = get_post_meta($id, 'weberino_answer');

	foreach($weberino_answer[0] as $key => $val) {
		$answers[$key]->answer = $val;
		$answers[$key]->id = $key;
	}

	echo json_encode($answers);
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
	$acceptable_answers = get_post_meta($id, 'weberino_acceptable_answer');

	$x = 0;
	foreach($answers[0] as $key => $val) {
		if($answer == $val) {
			$response['answer'][$x] = $val;
			$response['key'][$x] = $key;
			$response['correct'] = true;
			$x++;
		}

		$other_answers =  preg_split ('/(\s*,*\s*)*,+(\s*,*\s*)*/', $acceptable_answers[0][$key]);

		if(in_array($answer, $other_answers)) {
			$response['answer'][$x] = $val;
			$response['key'][$x] = $key;
			$response['correct'] = true;
			$x++;
		}
	}

	if ($x > 0) {
		echo json_encode( $response);
		die();
	}

	$response['correct'] = false;

	echo json_encode( $response);
	die();
}

add_action( 'wp_ajax_load_message', 'load_message' );
add_action( 'wp_ajax_nopriv_load_message', 'load_message' );

function load_message() {
	$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
	$response= [];
	$id = (int) $_POST['id'];
	$score = (int)  $_POST['score'];
	$weberino_feedback = get_post_meta($id, 'weberino_feedback');

	$response['message'] = $weberino_feedback[0][$score];

	echo json_encode( $response);
	die();
}

function hide_permalink() {
	global $post;
	if ($post->post_type == 'quiz') {
		return '<h2>Copy this shortcode and paste it into your post or page content: <span class="weberino-span">[weberino-quiz id=' . $post->ID .']</span></h2>';
	}

}
add_filter( 'get_sample_permalink_html', 'hide_permalink' );

function is_new_post() {
	if($_GET) {
		if(array_key_exists('action')){
			if ($_GET['action'] == 'edit') {
				return false;
			};
		}
	}
	return true;
}