<br />
<?php if (!empty($result_points)) { ?>
<table border="1" cellpadding="5" cellspacing="1">
	<tr>
		<td valign="top" scope="row"><strong>Name</strong></td>
		<td valign="top" scope="row"><strong>Date & Time</strong></td>
		<td valign="top"><strong>Nakshatra</strong></td>
		<td valign="top"><strong>Match Points</strong></td>
		<td valign="top"><strong>Result</strong></td>
	</tr>
	<?php foreach ($result_points as $k => $v) { ?>
	<tr>
		<td valign="top" scope="row"><?php echo $v['name']; ?></td>
		<td valign="top" scope="row"><?php echo $v['date']; ?></td>
		<td valign="top"><?php echo $v['naks']; ?> (Ref: #<?php echo $v['ref']; ?>)</td>
		<td valign="top"><?php echo $v['points']; ?></td>
		<td valign="top"><?php echo $v['result']; ?></td>
	</tr>
	<?php } ?>
</table>
<?php } ?>