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
		add_action('add_meta_boxes', [$this, 'weberino_add_questions']);
		add_action('save_post', [$this, 'weberino_save_postdata']);
		//enqueue js and css
	}

	function create_post_type() {
		register_post_type( 'quiz',
			array(
				'labels' => array(
					'name' => __( 'Quizzes' ),
					'singular_name' => __( 'Quiz' )
				),
				'public' => true,
				'has_archive' => true,
				'show_in_menu' => true
			)
		);
	}

	function weberino_add_questions() {
		add_meta_box(
			'weberino_question',
			'Add hints or questions',
			[$this, 'weberino_add_questions_html'],
			'quiz'
		);
	}

	function weberino_add_questions_html($post) {
		require_once plugin_dir_path( __FILE__ ) . 'templates/quiz-table.php';
	}

	function weberino_save_postdata($post_id) {
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
