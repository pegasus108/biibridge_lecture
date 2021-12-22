{*lock tables book, book_genre, book_series, opus, book_relate,book_ebookstores,book_format_book write;*}

update book set
	`publisher_no` = {$publisher_no},
	`name` = '{$name}',
	`kana` = '{$kana}',
	`volume` = '{$volume}',
	`sub_name` = '{$sub_name}',
	`sub_kana` = '{$sub_kana}',

	{if !$role.Image}
		{if $new_image}
			image='{$new_image}',
		{elseif $clear_image}
			image='',
		{/if}
	{/if}

	`product_code` = '{$product_code}',
	`isbn` = '{$isbn}',
	`e_isbn` = '{$e_isbn}',
	`magazine_code` = '{$magazine_code}',
	`c_code` = '{$c_code}',

	{if !$role.ReleaseDate}
		`book_date` = {if $book_date!='0000-00-00'}'{$book_date}'{else}null{/if},
		`release_date` = {if $release_date!='0000-00-00'}'{$release_date}'{else}null{/if},
	{/if}

	{if !$role.Version}
		`version` = '{$version}',
	{/if}

	{if !$role.Keyword}
		`keyword` = '{$keyword}',
	{/if}

	{if !$role.PublishDate}
		`public_status` = if('{$public_status}','{$public_status}',null),
		`public_date` = {if $public_date!='0000-00-00 00:00:00'}'{$public_date}'{else}current_timestamp{/if},
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
	`yondemill_book_sales_url` = {if $yondemill_book_sales_url}'{$yondemill_book_sales_url}'{else}null{/if},
	`ebook_status` = {if $ebook_status}1{else}null{/if},
	freeitem = {if $freeitem}'{$freeitem}'{else}null{/if},
	`note_epub` = '{$note_epub}',
	`note_image` = '{$note_image}',
	`note_logo` = '{$note_logo}',
	`note_text` = '{$note_text}',
	`printoffice` = {if $printoffice}'{$printoffice}'{else}null{/if},
	`sp_printoffice` = {if $sp_printoffice}'{$sp_printoffice}'{else}null{/if},
	`first_edition_issue_date` = {if $first_edition_issue_date}'{$first_edition_issue_date}'{else}null{/if},
	`first_edition_circulation_number` = {if $first_edition_circulation_number}'{$first_edition_circulation_number}'{else}null{/if},
	`questionnaire_url` = {if $questionnaire_url}'{$questionnaire_url}'{else}null{/if}

where
	book_no = {$book_no}
	and publisher_no = {$publisher_no}
;


{*if !$role.Label*}
	{if $deleteLabelList}

		delete from book_label
		where book_label_no in( {$deleteLabelList|@join:','} )
			and exists(
				select * from book
				where book.book_no = book_label.book_no
				and book.publisher_no = '{$publisher_no|escape}'
			);

	{/if}

	{foreach name=insert from=$insertLabelList item=label}

		select @myNo := coalesce(max(book_label_no), 0) + 1
		from book_label;

		insert into
			book_label(
				book_label_no,
				label_no,
				book_no,
				c_stamp
			)
			values(
				@myNo,
				'{$label|escape}',
				'{$book_no|escape}',
				current_timestamp
			);

	{/foreach}
{*/if*}

{if !$role.Genre}
	{if $deleteGenreList}

		delete from book_genre
		where book_genre_no in( {$deleteGenreList|@join:','} )
			and exists(
				select * from book
				where book.book_no = book_genre.book_no
				and book.publisher_no = {$publisher_no}
			);

	{/if}

	{foreach name=insert from=$insertGenreList item=genre}

		select @myNo := coalesce(max(book_genre_no), 0) + 1
		from book_genre;

		insert into
			book_genre(
				book_genre_no,
				genre_no,
				book_no,
				c_stamp
			)
			values(
				@myNo,
				{$genre},
				{$book_no},
				current_timestamp
			);

	{/foreach}
{/if}


{if !$role.Series}
	{if $deleteSeriesList}

		delete from book_series
		where book_series_no in( {$deleteSeriesList|@join:','} )
			and exists(
				select * from book
				where book.book_no = book_series.book_no
				and book.publisher_no = {$publisher_no}
			);

	{/if}

	{foreach name=insert from=$insertSeriesList item=book_series}

		select @myNo := coalesce(max(book_series_no), 0) + 1
		from book_series;

		insert into
			book_series(
				book_series_no,
				series_no,
				book_no,
				c_stamp
			)
			values(
				@myNo,
				{$book_series},
				{$book_no},
				current_timestamp
			);

	{/foreach}
{/if}



delete from opus
where opus.book_no = {$book_no}
	and exists(
		select * from book
		where book.book_no = opus.book_no
		and book.publisher_no = {$publisher_no}
	);

{foreach name=opus from=$opus_list item=opus}

	insert into
		opus(
			book_no,
			author_no,
			author_type_no,
			author_type_other,
			contributor_role,
			c_stamp
		)
		values(
			{$book_no}, -- book_no
			{$opus}, -- author_no
			{$author_type_list[$smarty.foreach.opus.index]}, -- author_type_no
			{if $author_type_list[$smarty.foreach.opus.index]=="16"&&$author_type_other[$smarty.foreach.opus.index]}'{$author_type_other[$smarty.foreach.opus.index]}'{else}NULL{/if}, -- author_type_other
			'{if $jpo_author_type_list[$opus]}{$jpo_author_type_list[$opus]}{/if}', -- contributor_role
			current_timestamp -- c_stamp
		);

{/foreach}


{if $deleteRelateList}

	delete from book_relate
	where(
		(
			book_relate_book_no in( {$deleteRelateList|@join:','} )
			and book_no = {$book_no}
		)or(
			book_no in( {$deleteRelateList|@join:','} )
			and book_relate_book_no = {$book_no}
		)
	)and exists(
		select * from book
		where book.book_no = book_relate.book_no
		and book.publisher_no = {$publisher_no}
	);

{/if}

{if $updateRelateList}
{foreach name=update from=$updateRelateList key=k item=v}
	update book_relate set `order` = {$v.order}
	where
		book_relate_book_no = {$v.id}
		and book_no = {$book_no}
	;
{/foreach}
{/if}

{foreach name=insert from=$insertRelateList item=book_relate}

	select @myNo := coalesce(max(book_relate_no), 0) + 1
	from book_relate;

	insert into
		book_relate(
			book_relate_no,
			book_no,
			book_relate_book_no,
			`order`,
			c_stamp
		)
		values(
			@myNo,
			{$book_no},
			{$book_relate.id},
			{$book_relate.order},
			current_timestamp
		);

	select @myNo := coalesce(max(book_relate_no), 0) + 1
	from book_relate;

	insert into
		book_relate(
			book_relate_no,
			book_no,
			book_relate_book_no,
			c_stamp
		)
		values(
			@myNo,
			{$book_relate.id},
			{$book_no},
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
			insert into book_ebookstores (book_no,ebookstores_id,url,public_status) values ({$book_no},{$pv.id},'{$pv.url}',1);
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

-- フォーマット紐付け
{if $deleteFormatList}
	delete from book_format_book
	where(
		(
			book_no_other in( {$deleteFormatList|@join:','} )
			and book_no = {$book_no}
		)or(
			book_no in( {$deleteFormatList|@join:','} )
			and book_no_other = {$book_no}
		)
	)and exists(
		select * from book
		where book.book_no = book_format_book.book_no
		and book.publisher_no = {$publisher_no}
	);
	{foreach name=deleteFormatList from=$deleteFormatList key=k item=v}
	update book set book_format = null,book_format_other = null where
		book_no = '{$v|escape}' and not exists(SELECT * FROM book_format_book where book_no = '{$v|escape}' or book_no_other =  '{$v|escape}');
	{/foreach}

{/if}

{if $updateFormatList}
{foreach name=updateformat from=$updateFormatList key=k item=v}
	{if $v.book_no_other}
	update book set
		{if $v.book_format}
		`book_format` = {$v.book_format}
		{/if}
		{if $v.book_format_other}
		{if $v.book_format}
		,
		{/if}
		`book_format_other` = '{$v.book_format_other}'
		{/if}

	where
		book_no = {$v.book_no_other}
	;
	{/if}
	{if $v.order}
	update book_format_book set
		`order` = {$v.order}

	where
		book_no_other = {$v.book_no_other}
		and book_no = {$book_no}
	;
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
			{$book_no},
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
			{$book_no},
			current_timestamp
		);

	update book set
		`book_format` = {$v.book_format},
		`book_format_other` = '{$v.book_format_other}'

	where
		book_no = {$v.book_no_other};
{/foreach}

{foreach name=spsite_delete from=$spsite_delete{$upkey} key=k item=v}
DELETE FROM special_site_link
WHERE special_site_link_no = '{$k}' AND book_no = '{$book_no}';
{/foreach}

{if $spsite{$upkey}}
{foreach name=spsite from=$spsite{$upkey} key=k item=v}
	{if $v}
	INSERT INTO
		special_site_link(
			publisher_no,
			book_no,
			`name`,
			`url`,
			`sort`,
			c_stamp
		)
		VALUES(
			{$publisher_no},
			{$book_no},
			{if !empty($spsitename{$upkey}[$k])}'{$spsitename{$upkey}[$k]}'{else}null{/if},
			'{$v}',
			{$smarty.foreach.spsite.iteration + $spsite_sort},
			current_timestamp
		);
	{/if}
{/foreach}
{/if}

{foreach name=cpsite_delete from=$cpsite_delete{$upkey} key=k item=v}
DELETE FROM campaign_site_link
WHERE campaign_site_link_no = '{$k}' AND book_no = '{$book_no}';
{/foreach}

{if $cpsite{$upkey}}
{foreach name=cpsite from=$cpsite{$upkey} key=k item=v}
	{if $v}
	INSERT INTO
		campaign_site_link(
			publisher_no,
			book_no,
			`url`,
			`sort`,
			c_stamp
		)
		VALUES(
			{$publisher_no},
			{$book_no},
			'{$v}',
			{$smarty.foreach.cpsite.iteration + $cpsite_sort},
			current_timestamp
		);
	{/if}
{/foreach}
{/if}

{*unlock tables;*}
