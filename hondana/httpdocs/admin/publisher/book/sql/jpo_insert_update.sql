{*lock tables book_jpo write;*}


INSERT INTO
book_jpo(
	book_no,
	measure_height,
	measure_width,
	measure_thickness,
	subject_code,
	imprint_name,
	imprint_collationkey,
	extent_value,
	unpriced_item_type,
	price_amount,
	price_effective_from,
	price_effective_until,
	on_sale_date,
	pre_order_limit,
	announcement_date,
	audience_code_value,
	audience_description,
	long_description,
	recent_publication_author,
	recent_publication_type,
	recent_publication_reader,
	recent_publication_content,
	recent_publication_date,
	containeditem,
	product_form_description,
	promotional_text,
	`language`,
	reselling,
	reselling_date,
	resellingdatecheck,
	supply_restriction_detail,
	notification_type,
	jan_code,
	publication_form,
	monthly_issue,
	completion,
	each_volume_name,
	each_volume_kana,
	weight,
	Issued_date,
	library_selection_content,
	Intermediary_company_handling,
	extent,
	award,
	readers_write,
	readers_write_page,
	production_notes_item,
	cd_dvd,
	bond_name,
	comments,
	band_contents,
	competition,
	separate_material,
	childrens_book_genre,
	font_size,
	ruby,
	percentage_of_manga,
	special_binding,
	trick,
	other_notices,
	number_of_first_edition,
	delivery_date,
	return_deadline,
	band,
	cover,
	binding_place,
	number_of_cohesions
)
VALUES(
	'{$book_no}',
	'{$measure_height}',
	'{$measure_width}',
	'{$measure_thickness}',
	'{$subject_code}',
	'{$imprint_name}',
	'{$imprint_collationkey}',
	{if $extent_value}'{$extent_value}'{else}NULL{/if},
	{if $unpriced_item_type}'{$unpriced_item_type}'{else}NULL{/if},
	{if $price_amount}'{$price_amount}'{else}NULL{/if},
	{if $price_effective_from_year && $price_effective_from_month && $price_effective_from_day}'{$price_effective_from_year}-{$price_effective_from_month}-{$price_effective_from_day}'{else}NULL{/if},
	{if $price_effective_until_year && $price_effective_until_month && $price_effective_until_day}'{$price_effective_until_year}-{$price_effective_until_month}-{$price_effective_until_day}'{else}NULL{/if},
	{if $on_sale_date_year && $on_sale_date_month && $on_sale_date_day}'{$on_sale_date_year}-{$on_sale_date_month}-{$on_sale_date_day}'{else}NULL{/if},
	{if $pre_order_limit_year && $pre_order_limit_month && $pre_order_limit_day}'{$pre_order_limit_year}-{$pre_order_limit_month}-{$pre_order_limit_day}'{else}NULL{/if},
	{if $announcement_date_year && $announcement_date_month && $announcement_date_day}'{$announcement_date_year}-{$announcement_date_month}-{$announcement_date_day}'{else}NULL{/if},
	'{$audience_code_value}',
	{if $audience_description}'{$audience_description}'{else}NULL{/if},
	'{$long_description}',
	'{$recent_publication_author}',
	'{$recent_publication_type}',
	'{$recent_publication_reader}',
	'{$recent_publication_content}',
	{if $recent_publication_date_year && $recent_publication_date_month && $recent_publication_date_day}'{$recent_publication_date_year}-{$recent_publication_date_month}-{$recent_publication_date_day}'{else}NULL{/if},
	'{$containeditem}',
	'{$product_form_description}',
	'{$promotional_text}',
	'{$language}',
	'{$reselling}',
	{if $reselling_date_year && $reselling_date_month && $reselling_date_day}'{$reselling_date_year}-{$reselling_date_month}-{$reselling_date_day}'{else}NULL{/if},
	{if $resellingdatecheck}'{$resellingdatecheck|escape}'{else}NULL{/if},
	{if $supply_restriction_detail}'{$supply_restriction_detail|string_format:"%02d"}'{else}null{/if},
	'{$notification_type}',
	'{$jan_code}',
	'{$publication_form}',
	'{$monthly_issue}',
	{if $completion != ''}'{$completion}'{else}null{/if},
	'{$each_volume_name}',
	'{$each_volume_kana}',
	'{$weight}',
	{if $Issued_date_year && $Issued_date_month && $Issued_date_day}'{$Issued_date_year}-{$Issued_date_month}-{$Issued_date_day}'{else}NULL{/if},
	'{$library_selection_content}',
	'{$Intermediary_company_handling}',
	{if $extent != ''}'{$extent}'{else}null{/if},
	'{$award}',
	{if $readers_write != ''}'{$readers_write}'{else}null{/if},
	'{$readers_write_page}',
	'{$production_notes_item}',
	{if $cd_dvd}'{$cd_dvd}'{else}NULL{/if},
	'{$bond_name}',
	'{$comments}',
	'{$band_contents}',
	'{$competition}',
	'{$separate_material}',
	'{$childrens_book_genre}',
	'{$font_size}',
	{if $ruby != ''}'{$ruby}'{else}null{/if},
	'{$percentage_of_manga}',
	'{$special_binding}',
	'{$trick}',
	'{$other_notices}',
	'{$number_of_first_edition}',
	{if $delivery_date_year && $delivery_date_month && $delivery_date_day}'{$delivery_date_year}-{$delivery_date_month}-{$delivery_date_day}'{else}NULL{/if},
	{if $return_deadline_year && $return_deadline_month && $return_deadline_day}'{$return_deadline_year}-{$return_deadline_month}-{$return_deadline_day}'{else}NULL{/if},
	{if $band != ''}'{$band}'{else}null{/if},
	{if $cover != ''}'{$cover}'{else}null{/if},
	'{$binding_place}',
	'{$number_of_cohesions}'
)
ON DUPLICATE KEY UPDATE
	measure_height=VALUES(measure_height),
	measure_width=VALUES(measure_width),
	measure_thickness=VALUES(measure_thickness),
	subject_code=VALUES(subject_code),
	imprint_name=VALUES(imprint_name),
	imprint_collationkey=VALUES(imprint_collationkey),
	extent_value=VALUES(extent_value),
	unpriced_item_type=VALUES(unpriced_item_type),
	price_amount=VALUES(price_amount),
	price_effective_from=VALUES(price_effective_from),
	price_effective_until=VALUES(price_effective_until),
	on_sale_date=VALUES(on_sale_date),
	pre_order_limit=VALUES(pre_order_limit),
	announcement_date=VALUES(announcement_date),
	audience_code_value=VALUES(audience_code_value),
	audience_description=VALUES(audience_description),
	long_description=VALUES(long_description),
	recent_publication_author=VALUES(recent_publication_author),
	recent_publication_type=VALUES(recent_publication_type),
	recent_publication_reader=VALUES(recent_publication_reader),
	recent_publication_content=VALUES(recent_publication_content),
	recent_publication_date=VALUES(recent_publication_date),
	containeditem=VALUES(containeditem),
	product_form_description=VALUES(product_form_description),
	promotional_text=VALUES(promotional_text),
	`language`=VALUES(`language`),
	reselling=VALUES(reselling),
	reselling_date=VALUES(reselling_date),
	resellingdatecheck=VALUES(resellingdatecheck),
	supply_restriction_detail=VALUES(supply_restriction_detail),
	notification_type=VALUES(notification_type),
	jan_code=VALUES(jan_code),
	publication_form=VALUES(publication_form),
	monthly_issue=VALUES(monthly_issue),
	completion=VALUES(completion),
	each_volume_name=VALUES(each_volume_name),
	each_volume_kana=VALUES(each_volume_kana),
	weight=VALUES(weight),
	Issued_date=VALUES(Issued_date),
	library_selection_content=VALUES(library_selection_content),
	Intermediary_company_handling=VALUES(Intermediary_company_handling),
	extent=VALUES(extent),
	award=VALUES(award),
	readers_write=VALUES(readers_write),
	readers_write_page=VALUES(readers_write_page),
	production_notes_item=VALUES(production_notes_item),
	cd_dvd=VALUES(cd_dvd),
	bond_name=VALUES(bond_name),
	comments=VALUES(comments),
	band_contents=VALUES(band_contents),
	competition=VALUES(competition),
	separate_material=VALUES(separate_material),
	childrens_book_genre=VALUES(childrens_book_genre),
	font_size=VALUES(font_size),
	ruby=VALUES(ruby),
	percentage_of_manga=VALUES(percentage_of_manga),
	special_binding=VALUES(special_binding),
	trick=VALUES(trick),
	other_notices=VALUES(other_notices),
	number_of_first_edition=VALUES(number_of_first_edition),
	delivery_date=VALUES(delivery_date),
	return_deadline=VALUES(return_deadline),
	band=VALUES(band),
	cover=VALUES(cover),
	binding_place=VALUES(binding_place),
	number_of_cohesions=VALUES(number_of_cohesions)
;



{*unlock tables;*}
