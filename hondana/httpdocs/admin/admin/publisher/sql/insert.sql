{include file="admin/admin/publisher/new/order.html" assign=order}
{include file="admin/admin/publisher/new/sitemap.html" assign=sitemap}
{include file="admin/admin/publisher/new/sitepolicy.html" assign=sitepolicy}

{*lock tables publisher, publisher_account, company_category, news_category, company, series, genre, publisher_netshop, publisher_payment write;*}


-- 出版社
select @myNo := coalesce(max(publisher_no), 0) + 1 as add_publisher_no
from publisher;


insert into
	publisher(
		publisher_no,
		name,
		kana,
		zipcode,
		address,
		tel,
		fax,
{if $logo.name}
		logo,
{/if}
		transaction_code,
		person_name,
		person_mail,
		copyright,
		url,
		id,

		design,
		linkage_person_name,
		linkage_person_mail,
		description,
		catchphrase,
		amazon_associates_id,
		rakuten_affiliate_id,
		seven_and_y_url,
		erupakabooks_url,
		google_analytics_tag,
		gapi_version,
		bookservice_no,
		contact_mail,
		cart_mail,

		from_person_unit,
		jpo,
		publisher_prefix,
		smp,

		c_stamp
		{if $cart}
		,cart
		{/if}
{if $yondemill_book_sales}
		,yondemill_book_sales
{/if}
{if $import_images}
		,import_images
{/if}
{if $ebook_store_status}
		,ebook_store_status
{/if}
	)
	values(
		@myNo,
		'{$name}',
		'{$kana}',
		'{$zipcode}',
		'{$address}',
		'{$tel}',
		'{$fax}',
{if $logo.name}
		'{$logo.name}',
{/if}
		'{$transaction_code}',
		'{$person_name}',
		'{$person_mail}',
		'{$copyright}',
		'{$url}',
		'{$id}',

		'{$design}',
		'{$linkage_person_name}',
		'{$linkage_person_mail}',
		'{$description}',
		'{$catchphrase}',
		'{$amazon_associates_id}',
		'{$rakuten_affiliate_id}',
		'{$seven_and_y_url}',
		'{$erupakabooks_url}',
		'{$google_analytics_tag}',
		'2.0',
		'{$bookservice_no}',
		'{$contact_mail}',
		'{$cart_mail}',

		'{$from_person_unit|escape}',
		'{$jpo|escape}',
		'{$publisher_prefix}',
		'{$smp}',

		current_timestamp
		{if $cart}
		,{$cart}
		{/if}
{if $yondemill_book_sales}
		,{$yondemill_book_sales}
{/if}
{if $import_images}
		,{$import_images}
{/if}
{if $ebook_store_status}
		,{$ebook_store_status}
{/if}
	);

select @myAccountNo := coalesce(max(publisher_account_no), 0) + 1
from publisher_account;

insert into
	publisher_account
set
	publisher_account_no = @myAccountNo,
	publisher_no = @myNo,
	role_no = 2,
	name = '{$name}',
	id = '{$id}',
	password = '{$pass}',
	is_default = '1';


-- 会社情報
select @ccNo := coalesce(max(company_category_no), 0) + 1
from company_category;

INSERT INTO company_category (company_category_no, publisher_no, name, display, lft, rgt, depth, c_stamp) VALUES
(@ccNo, @myNo, '会社情報カテゴリ', 0, 1, 18, 0, current_timestamp),
(@ccNo+1, @myNo, '会社概要', 1, 2, 3, 1, current_timestamp),
(@ccNo+2, @myNo, 'アクセスマップ', 1, 4, 5, 1, current_timestamp),
(@ccNo+3, @myNo, '採用情報', 1, 6, 7, 1, current_timestamp),
(@ccNo+4, @myNo, '沿革', 1, 8, 9, 1, current_timestamp),
(@ccNo+5, @myNo, 'ご注文方法', 1, 10, 11, 1, current_timestamp),
(@ccNo+6, @myNo, '個人情報保護への取り組み', 1, 12, 13, 1, current_timestamp),
(@ccNo+7, @myNo, 'サイトマップ', 1, 14, 15, 1, current_timestamp),
(@ccNo+8, @myNo, 'サイトポリシー', 1, 16, 17, 1, current_timestamp);


-- お知らせカテゴリ
select @ncNo := coalesce(max(news_category_no), 0) + 1
from news_category;

INSERT INTO news_category (news_category_no, publisher_no, news_fix_category_no, name, display, lft, rgt, depth, c_stamp) VALUES
(@ncNo, @myNo, 1, 'お知らせカテゴリ', 0, 1, 18, 0, current_timestamp),
(@ncNo+1, @myNo, 2, 'イベント情報', 1, 2, 3, 1, current_timestamp),
(@ncNo+2, @myNo, 3, 'メディアで紹介されました', 1, 4, 5, 1, current_timestamp),
(@ncNo+3, @myNo, 4, '書店様向け情報', 1, 6, 11, 1, current_timestamp),
(@ncNo+4, @myNo, 5, '注文書', 1, 7, 8, 2, current_timestamp),
(@ncNo+5, @myNo, 6, '店頭POP', 1, 9, 10, 2, current_timestamp),
(@ncNo+6, @myNo, 7, '重版情報', 1, 12, 13, 1, current_timestamp),
(@ncNo+7, @myNo, 8, '正誤表', 1, 14, 15, 1, current_timestamp),
(@ncNo+8, @myNo, 9, '採用情報', 1, 16, 17, 1, current_timestamp);


-- ネットショップ
select @pnNo := coalesce(max(publisher_netshop_no), 0) + 1
from publisher_netshop;

INSERT INTO publisher_netshop (publisher_netshop_no, publisher_no, netshop_no, public_status, display_order, c_stamp) VALUES
(@pnNo, @myNo, 1, 1, 1, current_timestamp),
(@pnNo+1, @myNo, 2, 1, 2, current_timestamp),
(@pnNo+2, @myNo, 4, 1, 4, current_timestamp),
(@pnNo+3, @myNo, 6, 1, 6, current_timestamp),
(@pnNo+4, @myNo, 8, 1, 8, current_timestamp),
(@pnNo+5, @myNo, 9, 1, 9, current_timestamp),
(@pnNo+6, @myNo, 10, 1, 10, current_timestamp),
(@pnNo+7, @myNo, 13, 1, 12, current_timestamp),
(@pnNo+8, @myNo, 14, 1, 13, current_timestamp),
(@pnNo+10, @myNo, 17, 1, 15, current_timestamp);


-- 決済方法（メールカート）
select @ppNo := coalesce(max(publisher_payment_no), 0) + 1
from publisher_payment;

INSERT INTO publisher_payment (publisher_payment_no, publisher_no, payment_option_no, public_status, c_stamp) VALUES
(@ppNo, @myNo, 1, 0, current_timestamp),
(@ppNo+1, @myNo, 2, 0, current_timestamp),
(@ppNo+2, @myNo, 3, 0, current_timestamp);


-- 会社情報
select @cNo := coalesce(max(company_no), 0) + 1
from company;

insert into company(company_no, publisher_no, company_category_no, name, value, public_status, public_date, c_stamp) values
(@cNo, @myNo, @ccNo+5, 'ご注文方法', '{$order}', 1, now(), current_timestamp),
(@cNo+1, @myNo, @ccNo+7, 'サイトマップ', '{$sitemap}', 1, now(), current_timestamp),
(@cNo+2, @myNo, @ccNo+8, 'サイトポリシー', '{$sitepolicy}', 1, now(), current_timestamp);


-- シリーズ
select @sNo := coalesce(max(series_no), 0) + 1
from series;

INSERT INTO series (series_no, publisher_no, name, kana, display, lft, rgt, depth, c_stamp) VALUES
(@sNo, @myNo, 'シリーズ', 'シリーズ', 0, 1, 2, 0, current_timestamp);


-- ジャンル
select @gNo := coalesce(max(genre_no), 0) + 1
from genre;

INSERT INTO genre (genre_no, publisher_no, name, display, lft, rgt, depth, c_stamp) VALUES
(@gNo, @myNo, 'ジャンル', 0, 1, 2, 0, current_timestamp);

-- レーベル
select @lNo := coalesce(max(label_no), 0) + 1
from label;

INSERT INTO label (label_no, publisher_no, name, display, lft, rgt, depth, c_stamp) VALUES
(@lNo, @myNo, 'レーベル', 0, 1, 2, 0, current_timestamp);


{*unlock tables;*}
