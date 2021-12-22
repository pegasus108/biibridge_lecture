select SQL_CALC_FOUND_ROWS
{foreach name=linkage from=$linkageList item=linkage}
	linkage_{$linkage.id}.process_date as {$linkage.id}_process_date,
	linkage_{$linkage.id}.set_date as {$linkage.id}_set_date,
	linkage_{$linkage.id}.`status` as {$linkage.id}_status,
{/foreach}
	b.*,
	date_format(b.book_date, '%Y/%m/%d') as b_stamp,
	ss.name as stock_status_name
#	,a.name as author_name

from
	book as b
	left join stock_status as ss
	on b.stock_status_no = ss.stock_status_no

{foreach name=linkage from=$linkageList item=linkage}
	left join linkage_{$linkage.id}
		on b.book_no = linkage_{$linkage.id}.book_no
{/foreach}
	
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
	
	{if $search_book_no}
	and b.book_no = {$search_book_no}
	{/if}
	
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

{elseif $order == 'book_date_asc'}
	b.book_date,

{/if}
	b.book_date desc


{if $limit}
	limit {if $offset}{$offset}, {/if}{$limit}
{/if}
;