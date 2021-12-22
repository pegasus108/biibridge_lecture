lock tables publisher_ebookstores write;

{foreach name=storeList from=$storeList key=k item=v}
{if $publisher_ebookstores_no[$k] == -1}
INSERT INTO `publisher_ebookstores` (
		`publisher_no`,
		`ebookstores_no`,
		`public_status`,
		`display_order`
	) VALUES (
		{$publisher_no},
		{$v},
		0,
		{$smarty.foreach.storeList.iteration}
	);
{else}
update `publisher_ebookstores` set
`ebookstores_no` = {$v},
`display_order` = {$smarty.foreach.storeList.iteration}

	where publisher_ebookstores_no = {$publisher_ebookstores_no[$k]};
{/if}
{/foreach}

unlock tables;