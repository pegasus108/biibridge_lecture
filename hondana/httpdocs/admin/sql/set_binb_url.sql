lock tables yondemill_book write;

{foreach name=list from=$list key=k item=v}
INSERT INTO yondemill_book(
	id,
	yondemill_id,
	browse_url,
	modified
) VALUES(
	{$v.yondemill_id},
	{$v.yondemill_id},
	'{$v.browse_url}',
	now()
) ON DUPLICATE KEY UPDATE
	browse_url = '{$v.browse_url}',
	modified = now()
;
{/foreach}

unlock tables;


