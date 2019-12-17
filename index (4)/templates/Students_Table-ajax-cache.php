<?php
	$rdata = array_map('to_utf8', array_map('nl2br', array_map('html_attr_tags_ok', $rdata)));
	$jdata = array_map('to_utf8', array_map('nl2br', array_map('html_attr_tags_ok', $jdata)));
?>
<script>
	$j(function() {
		var tn = 'Students_Table';

		/* data for selected record, or defaults if none is selected */
		var data = {
			ph_number: <?php echo json_encode(array('id' => $rdata['ph_number'], 'value' => $rdata['ph_number'], 'text' => $jdata['ph_number'])); ?>
		};

		/* initialize or continue using AppGini.cache for the current table */
		AppGini.cache = AppGini.cache || {};
		AppGini.cache[tn] = AppGini.cache[tn] || AppGini.ajaxCache();
		var cache = AppGini.cache[tn];

		/* saved value for ph_number */
		cache.addCheck(function(u, d) {
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'ph_number' && d.id == data.ph_number.id)
				return { results: [ data.ph_number ], more: false, elapsed: 0.01 };
			return false;
		});

		cache.start();
	});
</script>

