select
	b.book_no `no`,
	b.name,
	b.kana,
	b.volume,
	b.sub_name,
	b.sub_kana,
	b.isbn,
	b.magazine_code,
	b.c_code,

	b.version,
	b.page,
	b.price,
	b.outline,
	b.outline_abr,
	b.explain,
	b.content,

	b.public_date,
	b.public_date_order,
	b.public_status,
	b.new_status,
	b.keyword,
	b.next_book,
	b.recommend_status,
	b.recommend_order,
	b.cart_status,

	b.freeitem,

	bs.name book_size_name,
	ss.name stock_status_name,
	g.genre_name,
	s.series_name,
	o.author_name,
	date_format(b.book_date,'%Y-%m-%d') fb_date,
	date_format(b.release_date,'%Y-%m-%d') fr_date,
	substring_index(if(substring_index(o.author_name,',',1)=substring_index(o.author_name,',',0),null,substring_index(o.author_name,',',1)),',',-1) author_name1,
	substring_index(if(substring_index(o.author_name,',',2)=substring_index(o.author_name,',',1),null,substring_index(o.author_name,',',2)),',',-1) author_name2,
	substring_index(if(substring_index(o.author_name,',',3)=substring_index(o.author_name,',',2),null,substring_index(o.author_name,',',3)),',',-1) author_name3,
	substring_index(if(substring_index(o.author_name,',',4)=substring_index(o.author_name,',',3),null,substring_index(o.author_name,',',4)),',',-1) author_name4,
	substring_index(if(substring_index(o.author_name,',',5)=substring_index(o.author_name,',',4),null,substring_index(o.author_name,',',5)),',',-1) author_name5,
	substring_index(if(substring_index(o.author_name,',',6)=substring_index(o.author_name,',',5),null,substring_index(o.author_name,',',6)),',',-1) author_name6,
	substring_index(if(substring_index(o.author_name,',',7)=substring_index(o.author_name,',',6),null,substring_index(o.author_name,',',7)),',',-1) author_name7,
	substring_index(if(substring_index(o.author_name,',',8)=substring_index(o.author_name,',',7),null,substring_index(o.author_name,',',8)),',',-1) author_name8,
	substring_index(if(substring_index(o.author_name,',',9)=substring_index(o.author_name,',',8),null,substring_index(o.author_name,',',9)),',',-1) author_name9,
	substring_index(if(substring_index(o.author_name,',',10)=substring_index(o.author_name,',',9),null,substring_index(o.author_name,',',10)),',',-1) author_name10

from
	(
		select * from book
		where publisher_no = {$publisher_no}
		order by book_date desc
	) b

	left join book_size bs using(book_size_no)
	left join stock_status ss using(stock_status_no)

	left join(
		select
			book_genre.book_no,
			group_concat(genre.name separator '|') genre_name
		from book_genre
			left join genre
				on book_genre.genre_no = genre.genre_no
		where publisher_no = {$publisher_no}
		group by book_genre.book_no
	) g
		on b.book_no = g.book_no

	left join(
		select
			book_series.book_no,
			group_concat(series.name separator '|') series_name
		from book_series
			left join series
				on book_series.series_no = series.series_no
		where publisher_no = {$publisher_no}
		group by book_series.book_no
	) s
		on b.book_no = s.book_no

	left join(
		select
			ot.book_no,
			group_concat(concat(author.name,'^',author.kana,'^',if(author_type.author_type_no=16 , coalesce(ot.author_type_other,author_type.name) ,author_type.name)) separator ',') author_name
		from (select * from opus order by c_stamp) ot
			left join author
				on ot.author_no = author.author_no
			left join author_type
				on ot.author_type_no = author_type.author_type_no
		where publisher_no = {$publisher_no}
		group by ot.book_no
	) o
		on b.book_no = o.book_no
{if !$nolimit}
	limit {$offset},{$pagesize}
{/if}
;
