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

from info as n
WHERE n.info_no = {$info_no};