<!-- CSS Code: Place this code in the document's head (between the 'head' tags) -->
<style>
table.weberino-questions {
  width: 100%;
  background-color: #ffffff;
  border-collapse: collapse;
  border-width: 2px;
  border-color: #ffcc00;
  border-style: solid;
  color: #000000;
}

table.weberino-questions td, table.weberino-questions th {
  border-width: 2px;
  border-color: #ffcc00;
  border-style: solid;
  padding: 3px;
}

table.weberino-questions thead {
  background-color: #ffcc00;
}
</style>

<?php
$weberino_table_row = '<tr>
					      <td><input type="text" name="weberino_answer" value="" id="weberino_answerffdggsdfgsdfgg" clas="postbox"></td>
					      <td><input type="text" name="weberino_question" value="" class="postbox"></td>
					   </tr>';

$weberino_x = 1;
$weberino_table_body = "";


while ($weberino_x <= 10) {
    $value = get_post_meta($post->ID, 'weberino_question_' . $weberino_x, true);

    if(!$value) {
      $value = "";
    }

    $next_weberino_table_row = str_replace(['weberino_answer', 'weberino_question', 'value=""'], ['weberino_answer_' . $weberino_x,'weberino_question_'. $weberino_x, 'value="'. $value .'"'], $weberino_table_row);

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

