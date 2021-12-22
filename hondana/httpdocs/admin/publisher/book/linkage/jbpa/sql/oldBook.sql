SELECT
	data_type_1,
	isbn_1,
	category_1,
	name_1,
	kana_1,
	preliminary_1,
	sub_1,
	version_1,
	preliminary_2,
	series_1,
	series_kana_1,
	preliminary_3,
	author_1,
	author_type_1,
	author_kana_1,
	author_2,
	author_type_2,
	author_kana_2,
	author_3,
	author_type_3,
	author_kana_3,
	
	SUBSTRING(n.book_date_1,1,4) as book_date_1_year,
	SUBSTRING(n.book_date_1,5,2) as book_date_1_month,

	SUBSTRING(n.release_date_1,1,4) as release_date_1_year,
	SUBSTRING(n.release_date_1,5,2) as release_date_1_month,
	SUBSTRING(n.release_date_1,7,2) as release_date_1_day,
	
	book_size_2,
	if(LOCATE('x', book_size_2)=0,'',substring_index(REPLACE(book_size_2, 'cm', ''),'x',1)) as book_size_2_other_l,
	if(LOCATE('x', book_size_2)=0,'',substring_index(REPLACE(book_size_2, 'cm', ''),'x',-1)) as book_size_2_other_r,
	book_size_2,
	page_1,
	set_code_1,

	price_1,
	SUBSTRING(n.price_change_date_1,1,4) as price_change_date_1_year,
	SUBSTRING(n.price_change_date_1,5,2) as price_change_date_1_month,
	SUBSTRING(n.price_change_date_1,7,2) as price_change_date_1_day,

	price_special_1,
	SUBSTRING(n.price_special_policy_1,1,4) as price_special_policy_1_year,
	SUBSTRING(n.price_special_policy_1,5,2) as price_special_policy_1_month,
	SUBSTRING(n.price_special_policy_1,7,2) as price_special_policy_1_day,

	preliminary_4,
	preliminary_5,
	publisher_1,
	publisher_2,
	preliminary_6,
	out_status_1,
	SUBSTRING(n.out_date_1,1,4) as out_date_1_year,
	SUBSTRING(n.out_date_1,5,2) as out_date_1_month,
	SUBSTRING(n.out_date_1,7,2) as out_date_1_day,

	preliminary_7,
	SUBSTRING(n.preliminary_7,1,4) as preliminary_7_year,
	SUBSTRING(n.preliminary_7,5,2) as preliminary_7_month,
	SUBSTRING(n.preliminary_7,7,2) as preliminary_7_day,
	trade_code_1

FROM linkage_jbpa as n

WHERE n.book_no = {$book_no}
	and exists(
		select *
		from book
		where
		book.book_no = n.book_no
		and book.publisher_no = {$publisher_no}
	)
;
