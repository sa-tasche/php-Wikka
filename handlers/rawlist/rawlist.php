<?php
/**
 * FreeBASIC wikka custom handler
 * 
 * Display a raw list version of a wiki index page in a plain format
 * Pages supported:
 *    - PageIndex
 *    - RecentChanges
 *
 * Header Output format is:
 *   # freebasic wiki index
 *   # index: PageIndex
 *   # source: https://www.freebasic.net/wiki/wikka.php?wakka=
 *   # count: nnnn
 *   
 * Data output format is one page name per line:
 *    id . TAB . pagename . EOL
 */

// Send page as UTF-8 in cases where webserver is set up for different char set.
header('Content-Type: text/plain; charset=utf-8');

if ($this->HasAccess('read') && $this->page)
{
	/*
	 * Use the page name (tag) to determine what to do here.  Even though
	 * any page could potentially have the {{PageIndex}} and {{RecentChanges}}
	 * actions, historically these actions have only ever been on the
	 * PageIndex and RecentChanges pages, respectively.  That probably won't
	 * change anytime soon.
	 */

	$qry = "SELECT DISTINCT id, tag
			FROM " . $this->GetConfigValue('table_prefix') . "pages
			WHERE latest = 'Y'";
	
	if ($this->GetPageTag() == 'PageIndex')
	{
		$pages = $this->LoadAll( $qry . " ORDER BY tag" );
	}
	elseif ($this->GetPageTag() == 'RecentChanges')
	{
		$pages = $this->LoadAll( $qry . " ORDER BY id DESC" );
	}
	else
	{
		$pages = NULL;
	}

	// output header
	print( "# freebasic wiki index\r\n" );
	print( "# index: " . $this->tag . "\r\n" );
	print( "# source: " . $this->wikka_url . "\r\n" );
	print( "# count: " . count( $pages) . "\r\n" );
	
	// any results?
	if( $pages )
	{
		// output index
		foreach ($pages as $page)
		{
			// check user read privileges
			if (!$this->HasAccess('read', $page['tag']))
			{
				continue;
			}
			print( $page['id'] . "\t" . $page['tag']. "\r\n" );
		}
	}
}
?>
