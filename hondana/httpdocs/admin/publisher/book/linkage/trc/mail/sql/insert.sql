lock tables linkage_trc, series, author, linkage_shared_field write;

SELECT @myNo := ifnull(max(linkage_trc_no) , 0) + 1 from linkage_trc;

SELECT
	@mySeries := '' ,
	@myAuthor1Name := '' ,
	@myAuthor1Kana := '' ,
	@myAuthor1Val := '' ,
	@myAuthor2Name := '' ,
	@myAuthor2Kana := '' ,
	@myAuthor2Val := '';

SELECT @mySeries := name FROM series WHERE series_no = {$series_1};

SELECT
	@myAuthor1Name := name ,
	@myAuthor1Kana := kana ,
	@myAuthor1Val := value
FROM author WHERE author_no = {$author_1};

SELECT
	@myAuthor2Name := name ,
	@myAuthor2Kana := kana ,
	@myAuthor2Val := value
FROM author WHERE author_no = {$author_2};

INSERT INTO
	linkage_trc(
		linkage_trc_no,
		book_no,
		status,
		set_date,
		name_1,
		kana_1,
		sub_1,
		series_1,
		volume_1,
		author_1,
		author_2,
		author_kana_1,
		author_kana_2,
		author_note_1,
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
		price_special_policy_1,
		release_date_1,
		magazine_code_1,
		publisher_2,
		publisher_kana_2,
		issuer_1,
		issuer_kana_1,
		sub_kana_1,
		circulation_1,
		typist_1,
		typist_tel_1,
		type_date_1,
		explain_1,
		distribution_type_1,
		order_status_1,
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
	)
	VALUES(
		@myNo,
		{$book.book_no|escape},
		1,
		current_timestamp,
		'{$name_1}',
		'{$kana_1}',
		'{$sub_1}',
		'{$series_1}',
		'{$volume_1}',
		'{$author_1}',
		'{$author_2}',
		'{$author_kana_1}',
		'{$author_kana_2}',
		'{$author_note_1}',
		'{$author_note_2}',
		'{$isbn_1}',
		'{$price_1}',
		'{$genre_1}',
		'{$genre_2}',
		'{$book_size_1}',
		'{$page_1}',
		'{$content_1}',
		'{$version_release_1}',
		'{$price_special_1}',
		'{$price_special_policy_1_year}{$price_special_policy_1_month}{$price_special_policy_1_day}',
		'{$release_date_1_year}{$release_date_1_month}{$release_date_1_day}',
		{if $magazine_code_1_1}
		'{$magazine_code_1_1}-{$magazine_code_1_2}',
		{else}
		'',
		{/if}
		'{$publisher_2}',
		'{$publisher_kana_2}',
		'{$issuer_1}',
		'{$issuer_kana_1}',
		'{$sub_kana_1}',
		'{$circulation_1}',
		'{$typist_1}',
		'{$typist_tel_1}',
		'{$type_date_1_year}{$type_date_1_month}{$type_date_1_day}',
		'{$explain_1}',
		'{$distribution_type_1}',
		'{$order_status_1_year}{$order_status_1_month}{$order_status_1_day}',
		'{$target_1}',
		'{$rubi_status_1}',
		'{$note_1}',
		'{$win_info_1}',
		'{$reader_page_status_1}',
		'{$reader_page_1}',
		'{$unaccompanied_status_1}',
		'{$by_format_1}',
		'{$by_obi_1}',
		'{$representative_editor_1}',
		'{$representative_comment_1}',
		'{$conflicts_1}',
		'{$appendices_status_1}',
		'{$appendices_type_1}',
		'{$appendices_other_1}',
		'{$appendices_loan_1}',
		'{$appendix_1}'
	)
ON DUPLICATE KEY
	UPDATE
		status = values(status),
		set_date = values(set_date),
		process_date = current_timestamp,
		name_1 = values(name_1),
		kana_1 = values(kana_1),
		sub_1 = values(sub_1),
		series_1 = values(series_1),
		volume_1 = values(volume_1),
		author_1 = values(author_1),
		author_2 = values(author_2),
		author_kana_1 = values(author_kana_1),
		author_kana_2 = values(author_kana_2),
		author_note_1 = values(author_note_1),
		author_note_2 = values(author_note_2),
		isbn_1 = values(isbn_1),
		price_1 = values(price_1),
		genre_1 = values(genre_1),
		genre_2 = values(genre_2),
		book_size_1 = values(book_size_1),
		page_1 = values(page_1),
		content_1 = values(content_1),
		version_release_1 = values(version_release_1),
		price_special_1 = values(price_special_1),
		price_special_policy_1 = values(price_special_policy_1),
		release_date_1 = values(release_date_1),
		magazine_code_1 = values(magazine_code_1),
		publisher_2 = values(publisher_2),
		publisher_kana_2 = values(publisher_kana_2),
		issuer_1 = values(issuer_1),
		issuer_kana_1 = values(issuer_kana_1),
		sub_kana_1 = values(sub_kana_1),
		circulation_1 = values(circulation_1),
		typist_1 = values(typist_1),
		typist_tel_1 = values(typist_tel_1),
		type_date_1 = values(type_date_1),
		explain_1 = values(explain_1),
		distribution_type_1 = values(distribution_type_1),
		order_status_1 = values(order_status_1),
		target_1 = values(target_1),
		rubi_status_1 = values(rubi_status_1),
		note_1 = values(note_1),
		win_info_1 = values(win_info_1),
		reader_page_status_1 = values(reader_page_status_1),
		reader_page_1 = values(reader_page_1),
		unaccompanied_status_1 = values(unaccompanied_status_1),
		by_format_1 = values(by_format_1),
		by_obi_1 = values(by_obi_1),
		representative_editor_1 = values(representative_editor_1),
		representative_comment_1 = values(representative_comment_1),
		conflicts_1 = values(conflicts_1),
		appendices_status_1 = values(appendices_status_1),
		appendices_type_1 = values(appendices_type_1),
		appendices_other_1 = values(appendices_other_1),
		appendices_loan_1 = values(appendices_loan_1),
		appendix_1 = values(appendix_1)
;


{foreach from=$fieldList item=field}

select @myNo := ifnull(max(linkage_shared_field_no),0)+1 from linkage_shared_field;

INSERT INTO
	linkage_shared_field(
		linkage_shared_field_no,
	  	field_id,
		book_no,
		table_id
	)values(
		@myNo,
		'{$field}',
		{$book.book_no|escape},
		'trc'
	)
on DUPLICATE KEY
	UPDATE
		table_id = VALUES(table_id)
;

{/foreach}


unlock tables;
