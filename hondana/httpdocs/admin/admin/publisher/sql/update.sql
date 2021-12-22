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
{if $logo.name}
	logo = '{$logo.name}',
{/if}
	transaction_code = '{$transaction_code}',
	person_name = '{$person_name}',
	person_mail = '{$person_mail}',
	copyright = '{$copyright}',
	url = '{$url}',
	{if $admin_status}
	id = '{$id}',
	{/if}
	linkage_person_name = '{$linkage_person_name}',
	linkage_person_mail = '{$linkage_person_mail}',
	description = '{$description}',
	catchphrase = '{$catchphrase}',
	amazon_associates_id = '{$amazon_associates_id}',
	rakuten_affiliate_id = '{$rakuten_affiliate_id}',
	seven_and_y_url = '{$seven_and_y_url}',
	erupakabooks_url = '{$erupakabooks_url}',
	google_analytics_tag = '{$google_analytics_tag}',
	bookservice_no = '{$bookservice_no}',
	contact_mail = '{$contact_mail}',
	cart_mail = '{$cart_mail}',
	cart = {$cart},
	freeitem = {if $freeitem}'{$freeitem}'{else}null{/if},

	from_person_unit='{$from_person_unit|escape}',
	jpo='{$jpo|escape}',
	publisher_prefix='{$publisher_prefix}',
{if $smp_old != $smp}
	smp = '{$smp}',
{/if}
	book_notice_mail='{$book_notice_mail}',
	yondemill_book_sales = {if $yondemill_book_sales}{$yondemill_book_sales}{else}null{/if},
	import_images = {if $import_images}{$import_images}{else}null{/if},
	ebook_store_status = {if $ebook_store_status}{$ebook_store_status}{else}null{/if}

where
	publisher_no = {$publisher_no};

update
	publisher_account
set
	name = '{$name}',
	id = '{$id}',
	password = '{$pass}'
where
	publisher_no = {$publisher_no}
	and is_default = 1;

