SELECT n.*,
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
,'%s') as public_second

from news as n

WHERE n.news_no = '{$news_no|escape}'
	and n.publisher_no = '{$publisher_no|escape}';
