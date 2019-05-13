<?php
$weberino_table_row = '<tr>
					      <td><input type="text" name="weberino_question[%d]" value="%s" class="postbox"></td>
					      <td><input type="text" name="weberino_answer[%d]" value="%s" class="postbox"></td>
					   </tr>';

$x = 1;
$weberino_table_body = "";


while ($x <= 10) {
	$weberino_question = get_post_meta($post->ID, 'weberino_question', true);

    $question_value = "";

    if($weberino_question) {
	    $question_value = $weberino_question[$x];
    }

	$weberino_answer = get_post_meta($post->ID, 'weberino_answer', true);

	$answer_value = "";

    if(!$answer_value) {
        $answer_value = $weberino_answer[$x];
	}

    $next_weberino_table_row = sprintf($weberino_table_row, $x, $question_value, $x, $answer_value);

    $weberino_table_body .= $next_weberino_table_row;
    $x++;
}
?>

<table class="weberino-questions">
  <thead>
    <tr>
      <th>Question (or hint)</th>
      <th>Answer</th>
    </tr>
  </thead>
  <tbody>
    <?php echo $weberino_table_body; ?>
  </tbody>
</table>

