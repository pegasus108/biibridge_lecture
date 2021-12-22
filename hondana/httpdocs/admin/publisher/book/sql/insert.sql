{*lock tables book, book_genre, book_series, opus, book_relate,book_ebookstores write;*}


select @myNo := coalesce(max(book_no), 0) + 1
from book;

INSERT INTO
	`book` set
		`book_no` = @myNo,
		`publisher_no` = {$publisher_no},
		`name` = '{$name}',
		`kana` = '{$kana}',
		`volume` = '{$volume}',
		`sub_name` = '{$sub_name}',
		`sub_kana` = '{$sub_kana}',

	{if !$role.Image}
		`image` = '{$image}',
	{/if}
		`product_code` = '{$product_code}',
		`isbn` = '{$isbn}',
		`e_isbn` = '{$e_isbn}',
		`magazine_code` = '{$magazine_code}',
		`c_code` = '{$c_code}',

	{if !$role.ReleaseDate}
		`book_date` = {if $book_date!= '0000-00-00'}'{$book_date}'{else}null{/if},
		`release_date` = {if $release_date!= '0000-00-00'}'{$release_date}'{else}null{/if},
	{/if}

	{if !$role.Version}
		`version` = '{$version}',
	{/if}

	{if !$role.New}
		`new_status` = {if $new_status}1{else}NULL{/if},
		`next_book` = {if $next_book}1{else}NULL{/if},
	{/if}

	{if !$role.Recommend}
		`recommend_status` = {if $recommend_status}1{else}NULL{/if},
		`recommend_sentence` = '{$recommend_sentence}',
		`recommend_order` = if('{$recommend_order}','{$recommend_order}',2147483647),
	{/if}

	{if !$role.PublishDate}
		`public_date` = {if $public_date!= '0000-00-00 00:00:00'}'{$public_date}'{else}current_timestamp{/if},
		`public_status` = if('{$public_status}','{$public_status}',null),
	{/if}


	{if !$role.Keyword}
		`keyword` = '{$keyword}',
	{/if}

	{if !$role.Cart}
		`stock_status_no` = if('{$stock_status_no}','{$stock_status_no}',null),
		`cart_status` = if('{$cart_status}','{$cart_status}',null),
	{/if}
		`book_size_no` = if('{$book_size_no}','{$book_size_no}',null),
		`book_width` = if('{$book_width}','{$book_width}',null),
		`page` = if('{$page}','{$page}',null),
		`book_cover` = if('{$book_cover}','{$book_cover}',null),
		`book_band` = if('{$book_band}','{$book_band}',null),
		`book_slip` = if('{$book_slip}','{$book_slip}',null),
		`copyright` = '{$copyright}',
		`price` = if('{$price}','{$price}',null),
		`ebook_price` = if('{$ebook_price}','{$ebook_price}',null),
		`outline` = '{$outline}',
		`outline_abr` = '{$outline_abr}',
		`explain` = '{$explain}',
		`content` = '{$content}',
		`public_date_order` = if('{$public_date_order}'<>'','{$public_date_order}',2147483647),
		`book_format` = {if $this_format}{$this_format}{else}null{/if},
		`book_format_other` = '{$this_format_other}',
{if $yondemill_book_sales_url}
		`yondemill_book_sales_url` = '{$yondemill_book_sales_url}',
{/if}
{if $ebook_status}
		`ebook_status` = 1,
{/if}
		freeitem = {if $freeitem}'{$freeitem}'{else}null{/if},
		`note_epub` = '{$note_epub}',
		`note_image` = '{$note_image}',
		`note_logo` = '{$note_logo}',
		`note_text` = '{$note_text}',
		`printoffice` = {if $printoffice}'{$printoffice}'{else}null{/if},
		`sp_printoffice` = {if $sp_printoffice}'{$sp_printoffice}'{else}null{/if},
		`first_edition_issue_date` = {if $first_edition_issue_date}'{$first_edition_issue_date}'{else}null{/if},
		`first_edition_circulation_number` = {if $first_edition_circulation_number}'{$first_edition_circulation_number}'{else}null{/if},
		`questionnaire_url` = {if $questionnaire_url}'{$questionnaire_url}'{else}null{/if},
		c_stamp = current_timestamp;

{foreach name=book_label from=$book_label_list item=book_label}
	select @myBooklabelNo := coalesce(max(book_label_no), 0) + 1
	from book_label;

	insert into book_label(
		book_label_no,
		book_no,
		label_no,
		c_stamp
	)
	values(
		@myBooklabelNo,
		@myNo,
		'{$book_label}',
		current_timestamp
	);

{/foreach}

{foreach name=book_genre from=$book_genre_list item=book_genre}
	select @myBookGenreNo := coalesce(max(book_genre_no), 0) + 1
	from book_genre;

	insert into book_genre(
		book_genre_no,
		book_no,
		genre_no,
		c_stamp
	)
	values(
		@myBookGenreNo,
		@myNo,
		'{$book_genre}',
		current_timestamp
	);

{/foreach}


{foreach name=book_series from=$book_series_list item=book_series}
	select @myBookSeriesNo := coalesce(max(book_series_no), 0) + 1
	from book_series;

	insert into book_series(
		book_series_no,
		book_no,
		series_no,
		c_stamp
	)
	values(
		@myBookSeriesNo,
		@myNo,
		'{$book_series}',
		current_timestamp
	);

{/foreach}


{foreach name=opus from=$opus_list item=opus}
	insert into opus(
		book_no,
		author_no,
		author_type_no,
		author_type_other,
		contributor_role,
		c_stamp
	)
	values(
		@myNo, -- book_no
		{$opus}, -- author_no
		{$author_type_list[$smarty.foreach.opus.index]}, -- author_type_no
		{if $author_type_list[$smarty.foreach.opus.index]=="16"&&$author_type_other[$smarty.foreach.opus.index]}'{$author_type_other[$smarty.foreach.opus.index]}'{else}NULL{/if}, -- author_type_other
		'{if $jpo_author_type_list[$opus]}{$jpo_author_type_list[$opus]}{/if}', -- contributor_role
		current_timestamp -- c_stamp
	);


{/foreach}


{foreach name=book_relate from=$news_relate_list item=book_relate}
	select @myBookRelateNo := coalesce(max(book_relate_no), 0) + 1
	from book_relate;

	insert into book_relate(
		book_relate_no,
		book_no,
		book_relate_book_no,
		`order`,
		c_stamp
	)
	values(
		@myBookRelateNo,
		@myNo,
		{$book_relate},
		{$smarty.foreach.book_relate.iteration},
		current_timestamp
	);

	select @myBookRelateNo := coalesce(max(book_relate_no), 0) + 1
	from book_relate;

	insert into book_relate(
		book_relate_no,
		book_no,
		book_relate_book_no,
		c_stamp
	)
	values(
		@myBookRelateNo,
		{$book_relate},
		@myNo,
		current_timestamp
	);
{/foreach}

{if $ebook_status}
-- 電子書籍書店リンク
{foreach name=wrap from=$ebookstoreList key=pk item=pv}
	{if $pv.be_status == 1}
		-- 公開にチェックが入っている
		{if $pv.be_id != -1}
			-- レコードがある → 更新
			update book_ebookstores set url = '{$pv.url}',public_status = 1 where id = {$pv.be_id};
		{else}
			-- レコードがない → 追加
			insert into book_ebookstores (book_no,ebookstores_id,url,public_status) values (@myNo,{$pv.id},'{$pv.url}',1);
		{/if}
	{else}
		-- 公開にチェックが入っていない
		{if $pv.be_id != -1}
			-- レコードがある → 非公開にする
			update book_ebookstores set url = '{$pv.url}',public_status = -1 where id = {$pv.be_id};
		{/if}
	{/if}
{/foreach}
{/if}

{foreach name=insertformat from=$insertFormatList item=v}

	insert into
		book_format_book(
			book_no,
			book_no_other,
			`order`,
			c_stamp
		)
		values(
			@myNo,
			{$v.book_no_other},
			{$v.order},
			current_timestamp
		);

	insert into
		book_format_book(
			book_no,
			book_no_other,
			c_stamp
		)
		values(
			{$v.book_no_other},
			@myNo,
			current_timestamp
		);

	update book set
		`book_format` = {$v.book_format},
		`book_format_other` = '{$v.book_format_other}'

	where
		book_no = {$v.book_no_other};
{/foreach}

{if $spsite{$upkey}}
{foreach name=spsite from=$spsite{$upkey} item=v}
{if $v}
	insert into special_site_link(
		publisher_no,
		book_no,
		`name`,
		`url`,
		`sort`,
		c_stamp
	)
	values(
		{$publisher_no},
		@myNo,
		{if !empty($spsitename{$upkey}[$k])}'{$spsitename{$upkey}[$k]}'{else}null{/if},
		'{$v}',
		{$smarty.foreach.spsite.iteration},
		current_timestamp
	);
{/if}
{/foreach}
{/if}

{if $cpsite{$upkey}}
{foreach name=cpsite from=$cpsite{$upkey} item=v}
{if $v}
	insert into campaign_site_link(
		publisher_no,
		book_no,
		`url`,
		`sort`,
		c_stamp
	)
	values(
		{$publisher_no},
		@myNo,
		'{$v}',
		{$smarty.foreach.cpsite.iteration},
		current_timestamp
	);
{/if}
{/foreach}
{/if}

{*unlock tables;*}
