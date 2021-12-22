lock tables
	publisher,
	publisher_account
write;


update
	publisher
set
	name = '{$name}',
	kana = '{$kana}',
	zipcode = '{$zipcode}',
	address = '{$address}',
	tel = '{$tel}',
	fax = '{$fax}',
	transaction_code = '{$transaction_code}',
	person_name = '{$person_name}',
	person_mail = '{$person_mail}',
	copyright = '{$copyright}',
	url = '{$url}',
	linkage_person_name = '{$linkage_person_name}',
	linkage_person_mail = '{$linkage_person_mail}',
	description = '{$description}',
	catchphrase = '{$catchphrase}',
	contact_mail = '{$contact_mail}',
	cart_mail = '{$cart_mail}',

	ga_account = '{$ga_account}',
	ga_password = '{$ga_password}',
	ga_report = '{$ga_report}',

	publisher_prefix='{$publisher_prefix}',
	publisher_prefix_next ='{$publisher_prefix_next}',

	from_person_unit = '{$from_person_unit}',

	book_notice_mail = '{$book_notice_mail}'
where
	publisher_no = {$publisher_no};


{if $publisher_account.is_default || !$publisher.role}

	update
		publisher_account
	set
		password = '{$pass}'

	where
		publisher_no = {$publisher_no}
		and is_default = 1;

{/if}


unlock tables;
