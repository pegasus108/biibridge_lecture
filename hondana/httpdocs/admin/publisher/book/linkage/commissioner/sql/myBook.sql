SELECT
	p.transaction_code AS trade_code_1,
	p.name as issuer_1,
	p.kana as issuer_kana_1,
	p.linkage_person_name as typist_1,
	bs.name as series_1,
	bs.kana as series_kana_1,
	bs.name as sub_series_1,
	bs.name as sub_series_kana_1,
	n.name as name_1,
	n.kana as kana_1,
	n.volume as volume_2,
	n.sub_name as sub_1,
	n.sub_kana as sub_kana_1,
	n.volume as present_volume_1,
	n.outline as content_1,
	bsize.name as book_size_2,
	n.page as page_1,

	date_format(
		n.release_date
	,'%Y%m%d') as release_date_1,

	n.price as price_1,

	ceil(n.price * 1.08) as price_tax_1,

	n.isbn as isbn_1,
	n.c_code as category_1,
	n.magazine_code as magazine_code_1,

	substring_index(if(substring_index(author_name,',',1)=substring_index(author_name,',',0),null,substring_index(author_name,',',1)),',',-1) as author_1,
	substring_index(if(substring_index(author_name,',',2)=substring_index(author_name,',',1),null,substring_index(author_name,',',2)),',',-1) as author_2,
	substring_index(if(substring_index(author_name,',',3)=substring_index(author_name,',',2),null,substring_index(author_name,',',3)),',',-1) as author_3,

	substring_index(if(substring_index(author_type_no,',',1)=substring_index(author_type_no,',',0),null,substring_index(author_type_no,',',1)),',',-1) as author_type_no_1,
	substring_index(if(substring_index(author_type_no,',',2)=substring_index(author_type_no,',',1),null,substring_index(author_type_no,',',2)),',',-1) as author_type_no_2,
	substring_index(if(substring_index(author_type_no,',',3)=substring_index(author_type_no,',',2),null,substring_index(author_type_no,',',3)),',',-1) as author_type_no_3,

	substring_index(if(substring_index(author_type,',',1)=substring_index(author_type,',',0),null,substring_index(author_type,',',1)),',',-1) as author_type_1,
	substring_index(if(substring_index(author_type,',',2)=substring_index(author_type,',',1),null,substring_index(author_type,',',2)),',',-1) as author_type_2,
	substring_index(if(substring_index(author_type,',',3)=substring_index(author_type,',',2),null,substring_index(author_type,',',3)),',',-1) as author_type_3,

	substring_index(if(substring_index(author_type_other,',',1)=substring_index(author_type_other,',',0),null,substring_index(author_type_other,',',1)),',',-1) as author_type_other_1,
	substring_index(if(substring_index(author_type_other,',',2)=substring_index(author_type_other,',',1),null,substring_index(author_type_other,',',2)),',',-1) as author_type_other_2,
	substring_index(if(substring_index(author_type_other,',',3)=substring_index(author_type_other,',',2),null,substring_index(author_type_other,',',3)),',',-1) as author_type_other_3,

	substring_index(if(substring_index(author_kana,',',1)=substring_index(author_kana,',',0),null,substring_index(author_kana,',',1)),',',-1) as author_kana_1,
	substring_index(if(substring_index(author_kana,',',2)=substring_index(author_kana,',',1),null,substring_index(author_kana,',',2)),',',-1) as author_kana_2,
	substring_index(if(substring_index(author_kana,',',3)=substring_index(author_kana,',',2),null,substring_index(author_kana,',',3)),',',-1) as author_kana_3

FROM book as n
	left join publisher as p
		using(publisher_no)
	left join book_size as bsize
		using(book_size_no)
	LEFT join (
		select
			book_no,
			book_series.series_no,
			name,
			kana
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
			group_concat(author.name order by opus.c_stamp,opus.opus_no) as author_name,
			group_concat(author.kana order by opus.c_stamp,opus.opus_no) as author_kana,
			group_concat(opus.author_type_no order by opus.c_stamp,opus.opus_no) as author_type_no,
			group_concat(author_type.name order by opus.c_stamp,opus.opus_no) as author_type,
			group_concat(coalesce(opus.author_type_other,0) order by opus.c_stamp,opus.opus_no) as author_type_other

		from opus
			left join author
				on opus.author_no = author.author_no
			left join author_type
				on opus.author_type_no = author_type.author_type_no
		group BY book_no
	) as o
		on n.book_no = o.book_no

WHERE n.book_no = {$book_no}
	and n.publisher_no = {$publisher_no}
;
