<?php
$weberino_table_row = '<tr>
					      <td><input type="text" name="%s" value="%s" clas="postbox"></td>
					      <td><input type="text" name="%s" value="%s" class="postbox"></td>
					   </tr>';

$weberino_x = 1;
$weberino_table_body = "";


while ($weberino_x <= 10) {
    $question_value = get_post_meta($post->ID, 'weberino_question_' . $weberino_x, true);

    if(!$question_value) {
	    $question_value = "";
    }

	$answer_value = get_post_meta($post->ID, 'weberino_answer_' . $weberino_x, true);

	if(!$answer_value) {
		$answer_value = "";
	}

    $next_weberino_table_row = sprintf($weberino_table_row, 'weberino_question_'. $weberino_x, $question_value, 'weberino_answer_' . $weberino_x, $answer_value);

    $weberino_table_body .= $next_weberino_table_row;
    $weberino_x++;
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

