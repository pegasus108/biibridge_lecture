
{if count($genreList)}

select @myNo := ifnull(max(genre_no),0) from genre;
select @pR := rgt from genre where publisher_no = {$publisher_no} and lft = 1;

update genre set rgt = rgt+({$genreList|@count}*2) where publisher_no = {$publisher_no} and lft = 1;

insert into
	genre(
		genre_no,
		name,
		publisher_no,
		display,
		lft,
		rgt,
		depth,
		c_stamp
	)
	values
		{foreach name=genre from=$genreList item=genre}
			(
				@myNo+{$smarty.foreach.genre.iteration},
				'{$genre}',
				{$publisher_no},
				1,
				@pR + ({$smarty.foreach.genre.iteration}*2)-2,
				@pR + ({$smarty.foreach.genre.iteration}*2)-1,
				1,
				current_timestamp
			){if !$smarty.foreach.genre.last},{/if}
		{/foreach}
	;

{/if}




{if count($seriesList)}

select @myNo := ifnull(max(series_no),0) from series;
select @pR := rgt from series where publisher_no = {$publisher_no} and lft = 1;

update series set rgt = rgt+({$seriesList|@count}*2) where publisher_no = {$publisher_no} and lft = 1;

insert into
	series(
		series_no,
		name,
		publisher_no,
		display,
		lft,
		rgt,
		depth,
		c_stamp
	)
	values
		{foreach name=series from=$seriesList item=series}
			(
				@myNo+{$smarty.foreach.series.iteration},
				'{$series}',
				{$publisher_no},
				1,
				@pR + ({$smarty.foreach.series.iteration}*2)-2,
				@pR + ({$smarty.foreach.series.iteration}*2)-1,
				1,
				current_timestamp
			){if !$smarty.foreach.series.last},{/if}
		{/foreach}
	;

{/if}



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
