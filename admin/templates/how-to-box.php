<?php

	$weberino_how_to = get_post_meta($post->ID, 'weberino_how_to', true);

	if (is_new_post()) {
		$how_to = 'To get under way, click \'Start\'.

Once you have started the quiz, type an answer into the box and either hit enter or click the submit button. If you are right, it will fill in the correct slot in the table.

Keep entering more answers until you\'ve successfully completed the quiz - or the timer runs out.

If you do not want to play any more, just hit the \'Give up!\' button. You can then reveal the answers you missed - or have another go.';
	} else {
		$how_to = "";
	}

	if($weberino_how_to) {
		$how_to = $weberino_how_to;
	}

?>

<textarea style="width:100%" rows=10 name="weberino_how_to">
    <?php echo $how_to; ?>
</textarea>

