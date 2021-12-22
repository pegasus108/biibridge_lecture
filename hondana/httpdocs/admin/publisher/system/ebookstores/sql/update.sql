lock tables ebookstores write;

delete from ebookstores;

{foreach name=ebookstores from=$ebookstores item=ebs}
INSERT INTO ebookstores (
		`id`,
		`name`,
		`url`,
		`created_at`,
		`updated_at`
	) VALUES (
		{$ebs.id},
		'{$ebs.name}',
		'{$ebs.url}',
		'{$ebs.created_at}',
		'{$ebs.updated_at}'
	)
	ON DUPLICATE KEY UPDATE
		`name` = '{$ebs.name}',
		`url` = '{$ebs.url}',
		`created_at` = '{$ebs.created_at}',
		`updated_at` = '{$ebs.updated_at}'
	;
{/foreach}

unlock tables;
