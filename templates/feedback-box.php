<?php

$feedbacks = [
    'Not even one point! Oh dear',
    'I guess one is better than nothing :)',
    'Need to try harder!',
    'Not the end of the world :)',
	'Not the end of the world :)',
	'Not the end of the world :)',
	'Could do better :)',
	'A decent attempt :)',
	'You should be proud of yourself :)',
	'Well done, so close to a full house :)',
    'Fantastico! You know your stuff well!'
];


$weberino_table_row = '<tr>
                          <td>%s</td>
					      <td><input type="text" name="weberino_feedback[%d]" value="%s" class="postbox"></td>
					   </tr>';

$x = 0;
$weberino_table_body = "";


while ($x <= 10) {
	$weberino_feedback = get_post_meta($post->ID, 'weberino_feedback', true);

	if (is_new_post()) {
	   $feedback_value = $feedbacks[$x];
	} else {
		$feedback_value = "";
    }

	if($weberino_feedback) {
		$feedback_value = $weberino_feedback[$x];
	}

	$next_weberino_table_row = sprintf($weberino_table_row, $x, $x, $feedback_value);

	$weberino_table_body .= $next_weberino_table_row;
	$x++;
}
?>

<table class="weberino-questions">
    <thead>
    <tr>
        <th>Score</th>
        <th>Feedback</th>
    </tr>
    </thead>
    <tbody>
	<?php echo $weberino_table_body; ?>
    </tbody>
</table>

