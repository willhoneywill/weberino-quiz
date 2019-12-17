<?php

class Weberino_load_quiz  {


	public function __construct() {
		$this->register_actions();
	}

	public function register_actions() {

		add_action( 'wp_enqueue_scripts', [$this, 'enqueue_assets'] );


		if (! is_admin()) {
			add_shortcode( 'weberino-quiz', [$this,'weberino_load_quiz'] );
		}
	}

	public function register_ajax_actions()
	{
		add_action( 'wp_ajax_load_questions', [$this, 'load_questions'] );
		add_action( 'wp_ajax_nopriv_load_questions', [$this, 'load_questions'] );
		add_action( 'wp_ajax_load_answers', [$this, 'load_answers'] );
		add_action( 'wp_ajax_nopriv_load_answers', [$this, 'load_answers'] );
		add_action( 'wp_ajax_check_answer', [$this, 'check_answer'] );
		add_action( 'wp_ajax_nopriv_check_answer', [$this, 'check_answer'] );
		add_action( 'wp_ajax_load_message', [$this, 'load_message'] );
		add_action( 'wp_ajax_nopriv_load_message', [$this, 'load_message'] );
	}

	function enqueue_assets() {
		wp_enqueue_style( 'weberino_css', plugins_url('../assets/css/weberino.css' , __FILE__ ));

		wp_enqueue_script( 'vue', plugins_url('../assets/js/vue.js' , __FILE__ ), [], null, true);
		wp_enqueue_script('axios', plugins_url('../assets/js/axios.js' , __FILE__ ), ['vue'], '0.19.0', true);

		wp_enqueue_script( 'vue-weberino', plugins_url('../assets/js/weberino.js' , __FILE__ ), 'vue', null, true);
	}

	function weberino_load_quiz( $atts )
	{
		$title = get_the_title($atts['id']);
		$how_to_play = get_post_meta($atts['id'], 'weberino_how_to', true);
		global $post;
		$current_url = get_permalink($post->ID);

		require_once plugin_dir_path( __FILE__ ) . 'templates/quiz.php';
	}

	function load_questions()
	{
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

	function load_answers()
	{
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

	function check_answer()
	{
		$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
		$response= [];
		$id = (int) $_POST['id'];
		$answer = trim($_POST['answer']);
		$answers = get_post_meta($id, 'weberino_answer');
		$acceptable_answers = get_post_meta($id, 'weberino_acceptable_answer');

		$x = 0;
		foreach($answers[0] as $key => $val) {
			if(strcasecmp($answer,$val) == 0) {
				$response['answer'][$x] = $val;
				$response['key'][$x] = $key;
				$response['correct'] = true;
				$x++;
			}

			$other_answers =  preg_split ('/(\s*,*\s*)*,+(\s*,*\s*)*/', $acceptable_answers[0][$key]);

			foreach ($other_answers as $k => $v) {
				if(strcasecmp($answer,$v) == 0) {
					$response['answer'][$x] = $val;
					$response['key'][$x] = $key;
					$response['correct'] = true;
					$x++;
				}
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

	function load_message()
	{
		$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
		$response= [];
		$id = (int) $_POST['id'];
		$score = (int)  $_POST['score'];
		$weberino_feedback = get_post_meta($id, 'weberino_feedback');

		$response['message'] = $weberino_feedback[0][$score];

		echo json_encode( $response);
		die();
	}


}