SELECT
	n.name_1,
	n.kana_1,
	n.sub_1,
	n.series_1,
	n.volume_1,
	
	n.author_1,
	n.author_kana_1,
	author_note_1,

	n.author_2,
	n.author_kana_2,
	author_note_2,

	isbn_1,
	price_1,
	genre_1,
	genre_2,
	book_size_1,
	page_1,
	content_1,
	version_release_1,
	price_special_1,

	SUBSTRING(n.price_special_policy_1,1,4) as price_special_policy_1_year,
	SUBSTRING(n.price_special_policy_1,5,2) as price_special_policy_1_month,
	SUBSTRING(n.price_special_policy_1,7,2) as price_special_policy_1_day,

	SUBSTRING(n.release_date_1,1,4) as release_date_1_year,
	SUBSTRING(n.release_date_1,5,2) as release_date_1_month,
	SUBSTRING(n.release_date_1,7,2) as release_date_1_day,

	SUBSTRING(n.magazine_code_1,1,5) as magazine_code_1_1,
	SUBSTRING(n.magazine_code_1,7,2) as magazine_code_1_2,

	publisher_2,
	publisher_kana_2,
	issuer_1,
	issuer_kana_1,
	sub_kana_1,
	circulation_1,
	typist_1,
	typist_tel_1,

	SUBSTRING(n.type_date_1,1,4) as type_date_1_year,
	SUBSTRING(n.type_date_1,5,2) as type_date_1_month,
	SUBSTRING(n.type_date_1,7,2) as type_date_1_day,

	explain_1,
	distribution_type_1,

	SUBSTRING(n.order_status_1,1,4) as order_status_1_year,
	SUBSTRING(n.order_status_1,5,2) as order_status_1_month,
	SUBSTRING(n.order_status_1,7,2) as order_status_1_day,

	target_1,
	rubi_status_1,
	note_1,
	win_info_1,
	reader_page_status_1,
	reader_page_1,
	unaccompanied_status_1,
	by_format_1,
	by_obi_1,
	representative_editor_1,
	representative_comment_1,
	conflicts_1,
	appendices_status_1,
	appendices_type_1,
	appendices_other_1,
	appendices_loan_1,
	appendix_1

FROM linkage_trc as n
WHERE n.book_no = {$book_no}
	and exists(
		select *
		from book
		where
		book.book_no = n.book_no
		and book.publisher_no = {$publisher_no}
	)
;
