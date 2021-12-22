
select @myNo := 0;
select @mySize := 0;
select @myStatus := 0;

select @myNo := ifnull(max(book_no),0)+1 from book;
#select @mySize := book_size_no from book_size where book_size.name = '{$book[11]}';
#select @myStatus := stock_status_no from stock_status where stock_status.name = '{$book[27]}';

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
		`book_format`,
		`book_format_other`,
		`ebook_status`,
		c_stamp
	)
	values
		{foreach name=book from=$bookList item=book}
			{if count($book) >= 1}
				{if !$smarty.foreach.book.first},{/if}(
					@myNo+{$smarty.foreach.book.iteration},
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
					(select ifnull(book_size_no,0) from book_size where book_size.name = '{$book[11]}'),
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
					(select ifnull(stock_status_no,0) from stock_status where stock_status.name = '{$book[27]}'),
					{if $book[28]=='カート有効'}1{else}0{/if},
					{if $book[32]}{$book[33].id}{else}null{/if},
					{if $book[32]}'{$book[33].val}'{else}''{/if},
					{if $book[31]}1{else}null{/if},
					current_timestamp
				)
			{/if}
		{/foreach}
	;




{foreach name=book from=$bookList item=book}
{if count($book) >= 1}

{if $book[29]}
select @myBGNo := 0;
select @myBGNo := ifnull(max(book_genre_no),0) from book_genre;

insert into
	book_genre(
		book_genre_no,
		book_no,
		genre_no,
		c_stamp
	)
	values
		{foreach name=genre from=$book[29] item=genre}
			{if !$smarty.foreach.genre.first},{/if}(
				@myBGNo+{$smarty.foreach.genre.iteration},
				@myNo+{$smarty.foreach.book.iteration},
				{$genre},
				current_timestamp
			)
		{/foreach}
	;
{/if}

{if $book[30]}
select @myBSNo := 0;
select @mySeries := 0;
select @myBSNo := ifnull(max(book_series_no),0)+1 from book_series;

insert into
	book_series
	(
		book_series_no,
		book_no,
		series_no,
		c_stamp
	)
	values
		{foreach name=series from=$book[30] item=series}
			{if !$smarty.foreach.series.first},{/if}(
				@myBSNo+{$smarty.foreach.series.iteration},
				@myNo+{$smarty.foreach.book.iteration},
				{$series},
				current_timestamp
			)
		{/foreach}
	;
{/if}

{if $book[31]}
insert into
	book_ebookstores
	(
		`book_no`,
		`ebookstores_id`,
		`url`,
		`public_status`,
		`c_stamp`
	)
	values
		{foreach name=ebookstorelist from=$ebookstorelist item=v}
			{if !$smarty.foreach.ebookstorelist.first},{/if}
			(
				@myNo+{$smarty.foreach.book.iteration},
				{$v.id},
				'',
				{if $v.status}1{else}-1{/if},
				current_timestamp
			)
		{/foreach}
	;
{/if}

{if $book[32]}
update `book` set
	`book_format` = {$book[34].id},
	`book_format_other` = '{$book[34].val}'
	where book_no = {$book[32]};

insert into book_format_book (book_no,book_no_other,`order`,c_stamp) values (@myNo+{$smarty.foreach.book.iteration},{$book[32]},0,current_timestamp);
insert into book_format_book (book_no,book_no_other,`order`,c_stamp) values ({$book[32]},@myNo+{$smarty.foreach.book.iteration},0,current_timestamp);
{/if}


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
values(
	@myNo+{$smarty.foreach.book.iteration}, -- book_no
	@myA, -- author_no
	@myAT, -- author_type_no
	@myATO, -- author_type_other
	current_timestamp -- c_stamp
);

{/if}
{/foreach}

{/if}
{/foreach}
