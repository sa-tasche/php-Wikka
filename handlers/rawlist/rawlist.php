<?php
/**
 * FreeBASIC wikka custom handler
 * 
 * Display a raw list version of a wiki index page in a plain format
 *
 * Usage:
 *    wikka.php?wakka=<pagename>/rawlist
 *    wikka.php?wakka=<pagename>/rawlist&format=list
 *    wikka.php?wakka=<pagename>/rawlist&format=index
 *
 * <pagname>, follow pages allowed:
 *    - PageIndex
 *    - RecentChanges
 *
 * Optional Parameters:
 *    format=list             plain list (default if not specified)
 *    format=index            Output index format
 *
 * format=list output format, one entry per line:
 *    pagename . EOL
 * 
 * format=index output format:
 *    Index-Header
 *    Index-Entry...
 *  
 * Index-Header:
 *   # freebasic wiki index
 *   # index: PageIndex
 *   # source: https://www.freebasic.net/wiki/wikka.php?wakka=
 *   # count: nnnn
 *   
 * Index-Entry, one per line:
 *    id . TAB . pagename . EOL
 *
 */

// Send page as UTF-8 in cases where webserver is set up for different char set.
header('Content-Type: text/plain; charset=utf-8');

if ($this->HasAccess('read') && $this->page)
{

	$format = 'list';

	// &format=list or $format=index specified?
	if (isset($_GET['format']))
	{
		$a = $this->GetSafeVar('format', 'get');
		if ($a == 'list' || $a == 'index' )
		{
			$format = $a;
		}
	}
	
	/*
	 * Use the page name (tag) to determine what to do here.  Even though
	 * any page could potentially have the {{PageIndex}} and {{RecentChanges}}
	 * actions, historically these actions have only ever been on the
	 * PageIndex and RecentChanges pages, respectively.  That probably won't
	 * change anytime soon.
	 */

	$qry = "SELECT DISTINCT id, tag
			FROM " . $this->GetConfigValue('table_prefix') . "pages
			WHERE ((latest = 'Y') AND (CHAR_LENGTH(body) > 2))";
	
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

	// any results? count the number of pages first before we write the header
	$pagecount = 0;
	$cacheAccess= array();

	if( $pages )
	{
		foreach ($pages as $page)
		{
			if (!$this->HasAccess('read', $page['tag']))
			{
				$cacheAccess[$page['tag']] = false;
				continue;
			}
			$cacheAccess[$page['tag']] = true;
			$pagecount = $pagecount + 1;
		}
	}
	
	if ($format == 'index')
	{
		// output header
		print( "# freebasic wiki index\r\n" );
		print( "# index: " . $this->tag . "\r\n" );
		print( "# source: " . $this->wikka_url . "\r\n" );
		print( "# count: " . $pagecount . "\r\n" );
	}

	// any results?
	if( $pagecount > 0 )
	{
		// output index
		foreach ($pages as $page)
		{
			if (!$cacheAccess[$page['tag']]) 
			{
			 	continue;
			}

			if ($format == 'index')
			{
				print( $page['id'] . "\t" . $page['tag']. "\r\n" );
			}
			else
			{
				print( $page['tag']. "\r\n" );
			}
		}
	}
}
?>
