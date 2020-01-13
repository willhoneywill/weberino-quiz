<?php


class Weberino_edit_quiz {

	function __construct() {
		$this->register_actions();
	}

	function register_actions()
	{
		add_action('add_meta_boxes', [$this, 'add_questions']);
		add_action('save_post', [$this, 'save_postdata']);
		add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
		add_filter('get_sample_permalink_html', [$this,'hide_permalink'] );
	}

	function enqueue_admin_assets()
	{
		wp_enqueue_style( 'weberino_admin_css', plugins_url('../assets/css/weberino-admin.css' , __FILE__ ));
	}

	function hide_permalink()
	{
		global $post;
		if ($post->post_type == 'weberino-quiz') {
			return '<h2 class="weberino-alert">Copy this shortcode and paste it into your post or page content: <span class="weberino-span">[weberino-quiz id=' . $post->ID .']</span></h2>';
		}
	}

	function add_questions() {
		add_meta_box(
			'weberino_question',
			'Add hints or questions',
			[$this, 'add_questions_html'],
			'weberino-quiz'
		);

		add_meta_box(
			'weberino_how_to',
			'How to text',
			[$this, 'how_to_html'],
			'weberino-quiz'
		);

		add_meta_box(
			'weberino_feedback',
			'Add feedback depending on user\'s score',
			[$this, 'feedback_html'],
			'weberino-quiz'
		);
	}

	function add_questions_html($post)
	{
		require_once plugin_dir_path( __FILE__ ) . 'templates/quiz-table.php';
	}

	function how_to_html($post)
	{
		require_once plugin_dir_path( __FILE__ ) . 'templates/how-to-box.php';
	}

	function feedback_html($post)
	{
		require_once plugin_dir_path( __FILE__ ) . 'templates/feedback-box.php';
	}


	function save_postdata($post_id)
	{
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