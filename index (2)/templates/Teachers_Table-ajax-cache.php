<?php
	$rdata = array_map('to_utf8', array_map('nl2br', array_map('html_attr_tags_ok', $rdata)));
	$jdata = array_map('to_utf8', array_map('nl2br', array_map('html_attr_tags_ok', $jdata)));
?>
<script>
	$j(function() {
		var tn = 'Teachers_Table';

		/* data for selected record, or defaults if none is selected */
		var data = {
			Ph_number: <?php echo json_encode(array('id' => $rdata['Ph_number'], 'value' => $rdata['Ph_number'], 'text' => $jdata['Ph_number'])); ?>
		};

		/* initialize or continue using AppGini.cache for the current table */
		AppGini.cache = AppGini.cache || {};
		AppGini.cache[tn] = AppGini.cache[tn] || AppGini.ajaxCache();
		var cache = AppGini.cache[tn];

		/* saved value for Ph_number */
		cache.addCheck(function(u, d) {
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'Ph_number' && d.id == data.Ph_number.id)
				return { results: [ data.Ph_number ], more: false, elapsed: 0.01 };
			return false;
		});

		cache.start();
	});
</script>

