SELECT
	n.name AS name_1,
	n.kana AS kana_1,
	n.volume AS volume_1,
	n.sub_name AS sub_1,
	n.sub_kana AS sub_kana_1,
	n.outline AS content_1,
	n.price as price_1,
	
	n.isbn as isbn_1,
	
	n.page AS page_1,
	n.explain AS explain_1,

	date_format(
		n.release_date
	,'%Y%m%d') as release_date_1,

	if(n.magazine_code is null or n.magazine_code='','',
		concat(left(n.magazine_code,5),'-',right(n.magazine_code,2))
	) as magazine_code_1,

	p.name AS issuer_1,
	p.kana AS issuer_kana_1,

	bs.name as series_1,

	substring_index(if(substring_index(author_name,',',1)=substring_index(author_name,',',0),null,substring_index(author_name,',',1)),',',-1) as author_1,
	substring_index(if(substring_index(author_name,',',2)=substring_index(author_name,',',1),null,substring_index(author_name,',',2)),',',-1) as author_2,

	substring_index(if(substring_index(author_kana,',',1)=substring_index(author_kana,',',0),null,substring_index(author_kana,',',1)),',',-1) as author_kana_1,
	substring_index(if(substring_index(author_kana,',',2)=substring_index(author_kana,',',1),null,substring_index(author_kana,',',2)),',',-1) as author_kana_2,

	substring_index(if(substring_index(author_value,',',1)=substring_index(author_value,',',0),null,substring_index(author_value,',',1)),',',-1) as author_note_1,
	substring_index(if(substring_index(author_value,',',2)=substring_index(author_value,',',1),null,substring_index(author_value,',',2)),',',-1) as author_note_2,
	
	date_format(CURRENT_TIMESTAMP(),'%Y%m%d') as type_date_1

FROM book as n
	LEFT join publisher as p
		on n.publisher_no = p.publisher_no
	LEFT join (
		select
			book_no,
			book_series.series_no,
			name
		from book_series
			left join series
				on book_series.series_no = series.series_no
		group BY book_no
		order by book_series.c_stamp , series.name 
	) as bs
		on n.book_no = bs.book_no
	LEFT join (
		select
			book_no,
			opus.author_no,
			group_concat(name) as author_name,
			group_concat(kana) as author_kana,
			group_concat(value) as author_value
		from opus
			left join author
				on opus.author_no = author.author_no
		group BY book_no
		order by opus.c_stamp , author.name 
	) as o
		on n.book_no = o.book_no
	
WHERE n.book_no = {$book_no}
	and n.publisher_no = {$publisher_no}
;
