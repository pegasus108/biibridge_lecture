
{if count($authorList)}

select @myNo := ifnull(max(author_no),0) from author;

insert into
	author(
		author_no,
		publisher_no,
		name,
		kana,
		c_stamp
	)
	values
		{foreach name=author from=$authorList item=author}
			(
				@myNo+{$smarty.foreach.author.iteration},
				{$publisher_no},
				'{$author.name}',
				'{$author.kana}',
				current_timestamp
			){if !$smarty.foreach.author.last},{/if}
		{/foreach}
	;

{/if}
