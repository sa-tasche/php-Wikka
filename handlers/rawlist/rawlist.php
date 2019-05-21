<?php
/**
 * FreeBASIC wikka custom handler
 * Display a raw list version of a wiki page, i.e. the with no formatting.
 * Pages supported:
 * - PageIndex
 * - RecentChanges
 *
 */

// Send page as UTF-8 in cases where webserver is set up for different char set.
header('Content-Type: text/plain; charset=utf-8');

if ($this->HasAccess('read') && $this->page)
{
	if ($this->GetPageTag() == 'PageIndex')
	{
		if( $pages = $this->LoadPageTitles() )
		{
			foreach ($pages as $page)
			{
				// check user read privileges
				if (!$this->HasAccess('read', $page['tag']))
				{
					continue;
				}
				print($page['tag'] . "\r\n" );
			}
		}
	}
	elseif ($this->GetPageTag() == 'RecentChanges')
	{
		if( $pages = $this->LoadRecentlyChanged() )
		{
			foreach ($pages as $page)
			{
				// check user read privileges
				if (!$this->HasAccess('read', $page['tag']))
				{
					continue;
				}
				print($page['tag'] . "\r\n" );
			}
		}
	}
}
?>