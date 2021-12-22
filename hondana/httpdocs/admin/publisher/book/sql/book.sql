SELECT n.*, bh.is_enable honzuki,

date_format(n.synced, '%Y/%m/%d') as s_stamp,
date_format(n.sync_allowed, '%Y/%m/%d') as sa_stamp,
date_format(n.book_date, '%Y/%m/%d') as b_stamp,
date_format(n.release_date, '%Y/%m/%d') as r_stamp,

if((n.release_date > date(ADDDATE('{$jpoSyncTime}', INTERVAL 60 DAY))) ,'1','0') is_sync_before,


date_format(
	n.public_date
,'%Y') as public_year,

date_format(
	n.public_date
,'%m') as public_month,

date_format(
	n.public_date
,'%d') as public_day,

date_format(
	n.public_date
,'%H') as public_hour,

date_format(
	n.public_date
,'%i') as public_minute,

date_format(
	n.public_date
,'%s') as public_second,


date_format(
	n.book_date
,'%Y') as book_year,

date_format(
	n.book_date
,'%m') as book_month,

date_format(
	n.book_date
,'%d') as book_day,

date_format(
	n.release_date
,'%Y') as release_year,

date_format(
	n.release_date
,'%m') as release_month,

date_format(
	n.release_date
,'%d') as release_day,

date_format(
	n.first_edition_issue_date
,'%Y') as fei_year,

date_format(
	n.first_edition_issue_date
,'%m') as fei_month,

date_format(
	n.first_edition_issue_date
,'%d') as fei_day,

left(n.magazine_code,5) as magazine_code_1,
substring(n.magazine_code,6,2) as magazine_code_2


from book as n
left join book_honzuki bh on n.book_no = bh.book_no
WHERE n.book_no = {$book_no}
	and n.publisher_no = {$publisher_no};
