<?php
	// Extract {{fbdoc}} action's parameters from vars array
	// We support "item" and "value": {{fbdoc item="..." value="..."}}

	if (array_key_exists('item', $vars)) {
		$item = $vars['item'];
	} else {
		$item = $this->GetPageTag();
	}

	if (array_key_exists('value', $vars)) {
		$value = $vars['value'];
	} else {
		$value = 'fbdoc_value_unspecified';
	}

	$item = $this->ReturnSafeHTML($item);
	$value = $this->ReturnSafeHTML($value);

	switch ($item) {
	case 'title':
		if (array_key_exists('visible', $vars)) {
			$visible = $vars['visible'];
		} else {
			$visible = '1';
		}
		$visible = $this->ReturnSafeHTML($visible);
		$visible = filter_var($visible, FILTER_VALIDATE_BOOLEAN);

		if( $visible ) {
			print("<h2>$value</h2>");
		}
		break;

	case 'section':
		print("<b><u>$value</u></b>");
		break;

	case 'subsect':
		print("<b>$value</b>");
		break;

	case 'category':
	case 'keyword':
	case 'back':
		// If value contains a | char, split it up into page/name:
		// {{fbdoc ... value="page|name"}}
		$pagename = explode('|', $value, 2);
		$page = $pagename[0];
		if (count($pagename) > 1) {
			$name = $pagename[1];
		} else {
			$name = $page;
		}

		switch ($item) {
		case 'category':
			print("<a name=\"#cat_$page\"></a><b>$name:</b>");
			break;
		case 'keyword':
			print("<a href=\"" . $this->config['base_url'] . $page . "\">$name</a>");
			break;
		case 'back':
			print("<div align=\"center\">Back to <a href=\"" . $this->config['base_url'] . $page . "\">$name</a></div>");
			break;
		}

		break;

	case 'close':
	case 'tag':
	case 'filename':
		break;

	default:
		$itemTB = array( 
			'category' => 'Category', 
			'keyword' => 'Keyword', 
			'title' => 'Title', 
			'syntax' => 'Syntax', 
			'usage' => 'Usage',
			'param' => 'Parameters', 
			'ret'=> 'Return Value', 
			'desc' => 'Description', 
			'ex' => 'Examples', 
			'ver' => 'Version',
			'lang' => 'Dialect Differences',
			'target' => 'Platform Differences',
			'diff' => 'Differences from QB',
			'see' => 'See also', 
			'back' => 'Back',
			'close' => 'Close',
			'tag' => 'tag',
			'filename' => 'filename' 
		);

		if ($itemTB[$item]) {
			$name = $itemTB[$item];
			print("<b>$name:</b>");
		} else {
			print("<i>fbdoc action: unknown item " . $item . "</i>");
		}
	}
?>
