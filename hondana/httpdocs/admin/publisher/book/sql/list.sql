select SQL_CALC_FOUND_ROWS
	b.*, bh.is_enable honzuki,
	date_format(b.synced, '%Y/%m/%d') as s_stamp,
	date_format(b.sync_allowed, '%Y/%m/%d') as sa_stamp,
	date_format(b.book_date, '%Y/%m/%d') as b_stamp,
	date_format(b.release_date, '%Y/%m/%d') as r_stamp,
	if((b.release_date > date(ADDDATE('{$jpoSyncTime}', INTERVAL 180 DAY))) ,'1','0') is_sync_before,
	ss.name as stock_status_name

from
	book as b
	left join book_honzuki as bh on b.book_no = bh.book_no
	left join publisher p
		on b.publisher_no = p.publisher_no
	left join stock_status as ss
		on b.stock_status_no = ss.stock_status_no

{if $order == 'genre_asc' || $order == 'genre_desc'}
	left join (
		SELECT
			bg.book_genre_no,
			bg.book_no,
			g.name
		from book_genre as bg
			left join genre as g
				on bg.genre_no = g.genre_no
	) as g
		on b.book_no = g.book_no

{elseif $order == 'series_asc' || $order == 'series_desc'}
	left join (
		SELECT
			bs.book_series_no,
			bs.book_no,
			s.name
		from book_series as bs
			left join series as s
				on bs.series_no = s.series_no
	) as s
		on b.book_no = s.book_no
{/if}
/*
	left join (
		SELECT
			o.opus_no,
			o.book_no,
			a.name
		from opus as o
			left join author as a
				on o.author_no = a.author_no
		order by opus_no
	) as a
		on b.book_no = a.book_no
*/
where
	b.publisher_no = {$publisher_no}

	{if $search_display != ''}
	and {if $search_display}b.public_status = 1{else}(b.public_status <> 1 or b.public_status is null){/if}
	{/if}

	{if $search_title != ''}
	and b.`name` like '%{$search_title}%'
	{/if}

	{if $search_isbn != ''}
	and b.isbn = '{$search_isbn}'
	{/if}

	{if $search_magazine_code != ''}
	and b.magazine_code like '%{$search_magazine_code}%'
	{/if}

	{if $search_image != ''}
	{if $search_image=='1'}
	and (b.image != '')
	{else}
	and (b.image is null or b.image = '')
	{/if}
	{/if}

	{if $search_stock_status == 'not'}
	and (b.stock_status_no is null or b.stock_status_no = 0)
	{elseif $search_stock_status != ''}
	and b.stock_status_no = {$search_stock_status}
	{/if}

	{if $search_recommend_status != ''}
	{if $search_recommend_status=='1'}
	and (b.recommend_status = 1)
	{else}
	and (b.recommend_status is null or b.recommend_status = 0)
	{/if}
	{/if}

	{if $search_new_status != ''}
	{if $search_new_status=='1'}
	and (b.new_status = 1)
	{else}
	and (b.new_status is null or b.new_status = 0)
	{/if}
	{/if}

	{if $format && $format == 1}
	and b.ebook_status is not null
	{elseif $format && $format == 2}
	and b.ebook_status is null
	{/if}

	{if $search_sync_type != ''}
		{if $search_sync_type=='4'}
			{* 連携待ち *}
			and (
				not (b.release_date is null or b.release_date='0000-00-00 00:00:00')
				and if(( b.release_date > date(ADDDATE('{$jpoSyncTime}', INTERVAL 180 DAY))) ,true, false)
				and b.sync_allowed is not null
			)

		{elseif $search_sync_type=='2'}
			{* 未連携 *}
			and b.sync_allowed is null

		{elseif $search_sync_type=='1'}
			{* 連携中 *}
			and (
				not (b.release_date is null or b.release_date='0000-00-00 00:00:00')
				and if(( b.release_date <= date(ADDDATE('{$jpoSyncTime}', INTERVAL 180 DAY))) ,true, false)
				and b.sync_allowed is not null
			)
		{/if}
	{/if}

	{if $search_author != ''}
	and exists(
		select *
		from opus as o
			left join author as a
			on o.author_no = a.author_no
		where
			replace(replace(a.name,' ',''),'　','') like replace(replace('%{$search_author}%',' ',''),'　','')
			and o.book_no = b.book_no
	)
	{/if}

	{if $search_genre == 'not'}
	and not exists(
		select *
		from book_genre as bg
			left join genre as g
			on bg.genre_no = g.genre_no
		where
			bg.book_no = b.book_no
	)
	{elseif $search_genre != ''}
	and exists(
		select *
		from
			(
				select lft ,rgt from genre where genre_no = {$search_genre}
			) as gp,
			book_genre as bg
			left join genre as g
			on bg.genre_no = g.genre_no
		where
			bg.book_no = b.book_no
			and g.lft >= gp.lft and g.rgt <= gp.rgt
	)
	{/if}

	{if $search_series == 'not'}
	and not exists(
		select *
		from book_series as bs
			left join series as s
			on bs.series_no = s.series_no
		where
			bs.book_no = b.book_no
	)
	{elseif $search_series != ''}
	and exists(
		select *
		from
			(
				select lft ,rgt from series where series_no = {$search_series}
			) as sp,
			book_series as bs
			left join series as s
			on bs.series_no = s.series_no
		where
			bs.book_no = b.book_no
			and s.lft >= sp.lft and s.rgt <= sp.rgt

	)
	{/if}
    {if $search_label == 'not'}
	and not exists(
		select *
		from book_label as bl
			left join label as l
			on bl.label_no = l.label_no
		where
			bl.book_no = b.book_no
	)
	{elseif $search_label != ''}
	and exists(
		select *
		from
			(
				select lft ,rgt from label where label_no = {$search_label}
			) as lp,
			book_label as bl
			left join label as l
			on bl.label_no = l.label_no
		where
			bl.book_no = b.book_no
			and l.lft >= lp.lft and l.rgt <= lp.rgt

	)
	{/if}

group by b.book_no

order by

{if $order == 'display_asc'}
	b.public_status,

{elseif $order == 'display_desc'}
	b.public_status desc,

{elseif $order == 'new_status_asc'}
	b.new_status,

{elseif $order == 'new_status_desc'}
	b.new_status desc,

{elseif $order == 'recommend_asc'}
	b.recommend_status,

{elseif $order == 'recommend_desc'}
	b.recommend_status desc,

{elseif $order == 'next_book_asc'}
	b.next_book,

{elseif $order == 'next_book_desc'}
	b.next_book desc,

{elseif $order == 'recommend_status_asc'}
	b.recommend_status,

{elseif $order == 'recommend_status_desc'}
	b.recommend_status desc,

{elseif $order == 'image_asc'}
	b.image,

{elseif $order == 'image_desc'}
	b.image desc,

{elseif $order == 'stock_status_asc'}
	ss.name,

{elseif $order == 'stock_status_desc'}
	ss.name desc,

{elseif $order == 'cart_status_asc'}
	b.cart_status,

{elseif $order == 'cart_status_desc'}
	b.cart_status desc,

{elseif $order == 'title_asc'}
	b.name,

{elseif $order == 'title_desc'}
	b.name desc,

{elseif $order == 'sub_title_asc'}
	b.sub_name,

{elseif $order == 'sub_title_desc'}
	b.sub_name desc,

{elseif $order == 'isbn_asc'}
	b.isbn,

{elseif $order == 'isbn_desc'}
	b.isbn desc,

{elseif $order == 'magazine_code_asc'}
	b.magazine_code,

{elseif $order == 'magazine_code_desc'}
	b.magazine_code desc,

{elseif $order == 'genre_asc'}
	g.name,

{elseif $order == 'genre_desc'}
	g.name desc,

{elseif $order == 'series_asc'}
	s.name,

{elseif $order == 'series_desc'}
	s.name desc,

{elseif $order == 'author_asc'}
	a.name,

{elseif $order == 'author_desc'}
	a.name desc,

{elseif $order == 'honzuki_asc'}
	bh.is_enable,

{elseif $order == 'honzuki_desc'}
	bh.is_enable desc,

{elseif $order == 'book_date_asc'}
	b.book_date,

{elseif $order == 'synced_asc'}
		if((
			b.release_date is null or b.release_date='0000-00-00 00:00:00'
			or if(( b.release_date < date('{$jpoSyncTime}')),true, false)
			or (
				if(( b.release_date > date(ADDDATE('{$jpoSyncTime}', INTERVAL 180 DAY))) ,true, false)
				and b.sync_allowed is null
			)
		),1,0) desc,

		if((
			not (b.release_date is null or b.release_date='0000-00-00 00:00:00')
			and not if(( b.release_date < date('{$jpoSyncTime}')),true, false)
			and if(( b.release_date > date(ADDDATE('{$jpoSyncTime}', INTERVAL 180 DAY))) ,true, false)
			and b.sync_allowed is not null
		),1,0) desc,

		if((
			not(
				b.release_date is null or b.release_date='0000-00-00 00:00:00'
				or if(( b.release_date < date('{$jpoSyncTime}')),true, false)
				or if((b.release_date > date(ADDDATE('{$jpoSyncTime}', INTERVAL 180 DAY))) ,true, false)
			) and (
				b.synced is null and b.sync_allowed is null
				or (
					p.transaction_code is null or p.transaction_code=''
					or p.transaction_code is null or p.transaction_code=''
					or p.publisher_prefix is null or p.publisher_prefix=''
					or p.from_person_unit is null or p.from_person_unit=''
				)
			)
		),1,0) desc,

		if((
			not(
				b.release_date is null or b.release_date='0000-00-00 00:00:00'
				or if(( b.release_date < date('{$jpoSyncTime}')),true, false)
				or if((b.release_date > date(ADDDATE('{$jpoSyncTime}', INTERVAL 180 DAY))) ,true, false)
			) and (
				(b.synced is not null or b.sync_allowed is not null)
				and not(
					p.transaction_code is null or p.transaction_code=''
					or p.transaction_code is null or p.transaction_code=''
					or p.publisher_prefix is null or p.publisher_prefix=''
					or p.from_person_unit is null or p.from_person_unit=''
				)
			)
		),1,0) desc,

{elseif $order == 'synced_desc'}
		if((
			b.release_date is null or b.release_date='0000-00-00 00:00:00'
			or if(( b.release_date < date('{$jpoSyncTime}')),true, false)
			or (
				if(( b.release_date > date(ADDDATE('{$jpoSyncTime}', INTERVAL 180 DAY))) ,true, false)
				and b.sync_allowed is null
			)
		),1,0) asc,

		if((
			not (b.release_date is null or b.release_date='0000-00-00 00:00:00')
			and not if(( b.release_date < date('{$jpoSyncTime}')),true, false)
			and if(( b.release_date > date(ADDDATE('{$jpoSyncTime}', INTERVAL 180 DAY))) ,true, false)
			and b.sync_allowed is not null
		),1,0) asc,

		if((
			not(
				b.release_date is null or b.release_date='0000-00-00 00:00:00'
				or if(( b.release_date < date('{$jpoSyncTime}')),true, false)
				or if((b.release_date > date(ADDDATE('{$jpoSyncTime}', INTERVAL 180 DAY))) ,true, false)
			) and (
				b.synced is null and b.sync_allowed is null
				or (
					p.transaction_code is null or p.transaction_code=''
					or p.transaction_code is null or p.transaction_code=''
					or p.publisher_prefix is null or p.publisher_prefix=''
					or p.from_person_unit is null or p.from_person_unit=''
				)
			)
		),1,0) asc,

		if((
			not(
				b.release_date is null or b.release_date='0000-00-00 00:00:00'
				or if(( b.release_date < date('{$jpoSyncTime}')),true, false)
				or if((b.release_date > date(ADDDATE('{$jpoSyncTime}', INTERVAL 180 DAY))) ,true, false)
			) and (
				(b.synced is not null or b.sync_allowed is not null)
				and not(
					p.transaction_code is null or p.transaction_code=''
					or p.transaction_code is null or p.transaction_code=''
					or p.publisher_prefix is null or p.publisher_prefix=''
					or p.from_person_unit is null or p.from_person_unit=''
				)
			)
		),1,0) asc,

{/if}
	b.book_date desc


{if $limit}
	limit {if $offset}{$offset}, {/if}{$limit}
{/if}
;