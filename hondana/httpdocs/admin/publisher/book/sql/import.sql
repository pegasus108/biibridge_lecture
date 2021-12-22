{*lock tables
genre,
book_genre,
series,
book_series,
author,
auhtor_type,
opus,
book,
book_size,
book_status
write;
*}

{foreach name=genre from=$genreList item=genre}

select @myNo := ifnull(max(genre_no),0)+1 from genre;

select @pR := rgt from genre where publisher_no = {$publisher_no} and lft = 1;

update genre set rgt = (@pR + 2) where publisher_no = {$publisher_no} and lft = 1;

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
	values(
		@myNo,
		'{$genre}',
		{$publisher_no},
		1,
		@pR,
		(@pR+1),
		1,
		current_timestamp
	);

{/foreach}


{foreach name=series from=$seriesList item=series}

select @myNo := ifnull(max(series_no),0)+1 from series;

select @pR := rgt from series where publisher_no = {$publisher_no} and lft = 1;

update series set rgt = (@pR + 2) where publisher_no = {$publisher_no} and lft = 1;

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
	values(
		@myNo,
		'{$series}',
		{$publisher_no},
		1,
		@pR,
		(@pR+1),
		1,
		current_timestamp
	);

{/foreach}


{foreach name=author from=$authorList item=author}

select @myNo := ifnull(max(author_no),0)+1 from author;

insert into
	author(
		author_no,
		publisher_no,
		name,
		kana,
		c_stamp
	)
	values(
		@myNo,
		{$publisher_no},
		'{$author.name}',
		'{$author.kana}',
		current_timestamp
	);

{/foreach}


{foreach name=book from=$bookList item=book}
{if count($book) >= 1}

select @myNo := 0;
select @mySize := 0;
select @myStatus := 0;
select @myNo := ifnull(max(book_no),0)+1 from book;
select @mySize := book_size_no from book_size where book_size.name = '{$book[11]}';
select @myStatus := stock_status_no from stock_status where stock_status.name = '{$book[27]}';

INSERT INTO
	`book`(
		`book_no`,
		`publisher_no`,
		`name`,
		`kana`,
		`volume`,
		`sub_name`,
		`sub_kana`,
		`image`,
		`isbn`,
		`magazine_code`,
		`c_code`,
		`book_date`,
		`release_date`,
		`version`,
		`book_size_no`,
		`page`,
		`price`,
		`outline`,
		`outline_abr`,
		`explain`,
		`content`,
		`public_date`,
		`public_date_order`,
		`public_status`,
		`new_status`,
		`keyword`,
		`next_book`,
		`recommend_status`,
		`recommend_sentence`,
		`recommend_order`,
		`stock_status_no`,
		`cart_status`,
		c_stamp
	)
	values(
		@myNo,
		{$publisher_no},
		'{$book[0]}',
		'{$book[1]}',
		'{$book[2]}',
		'{$book[3]}',
		'{$book[4]}',
		'',
		'{$book[5]}',
		'{$book[6]}',
		'{$book[7]}',
		cast('{$book[8]}' as datetime),
		cast('{$book[9]}' as datetime),
		'{$book[10]}',
		@mySize,
		{if $book[12]}'{$book[12]}'{else}null{/if},
		{if $book[13]}'{$book[13]}'{else}null{/if},
		'{$book[14]}',
		'{$book[15]}',
		'{$book[16]}',
		'{$book[17]}',
		{if $book[18]!=''}'{$book[18]}'{else}current_timestamp{/if},
		if('{$book[19]}'='指定なし' or '{$book[19]}'='',2147483647,'{$book[19]}'),
		{if $book[20]=='公開'}1{else}0{/if},
		{if $book[21]=='新刊'}1{else}0{/if},
		'{$book[22]}',
		{if $book[23]=='これから出る本'}1{else}0{/if},
		{if $book[24]=='おすすめ'}1{else}0{/if},
		'{$book[25]}',
		if('{$book[26]}'='指定なし' or '{$book[26]}'='',2147483647,'{$book[26]}'),
		@myStatus,
		{if $book[28]=='カート有効'}1{else}0{/if},
		current_timestamp
	);

{foreach name=genre from=$book[29] item=genre}

select @myBGNo := 0;
select @myGenre := 0;
select @myBGNo := ifnull(max(book_genre_no),0)+1 from book_genre;
select @myGenre := genre_no from genre where publisher_no = {$publisher_no} and genre.name = '{$genre}';

insert into
	book_genre(
		book_genre_no,
		book_no,
		genre_no,
		c_stamp
	)
	values(
		@myBGNo,
		@myNo,
		@myGenre,
		current_timestamp
	);

{/foreach}


{foreach name=series from=$book[30] item=series}

select @myBSNo := 0;
select @mySeries := 0;
select @myBSNo := ifnull(max(book_series_no),0)+1 from book_series;
select @mySeries := series_no from series where publisher_no = {$publisher_no} and series.name = '{$series}';

insert into
	book_series(
		book_series_no,
		book_no,
		series_no,
		c_stamp
	)
	values(
		@myBSNo,
		@myNo,
		@mySeries,
		current_timestamp
	);

{/foreach}


{foreach name=bookItem from=$book item=bookItem}
{if $smarty.foreach.bookItem.index >= 31 && $bookItem[0]}

select @myA := 0;
select @myAT := 16;
select @myATO := NULL;
select @myA := author_no from author where publisher_no = {$publisher_no} and author.name = '{$bookItem[0]}' and author.kana = '{$bookItem[1]}';
select @myAT := author_type_no from author_type where author_type.name = '{$bookItem[2]}';
select @myATO := if(@myAT=16 , '{$bookItem[2]}' , NULL);

insert into opus(
	book_no,
	author_no,
	author_type_no,
	author_type_other,
	c_stamp
)
SELECT
	@myNo, -- book_no
	@myA, -- author_no
	@myAT, -- author_type_no
	@myATO, -- author_type_other
	current_timestamp -- c_stamp

	FROM opus;

{/if}
{/foreach}

{/if}
{/foreach}


{*unlock tables;*}
