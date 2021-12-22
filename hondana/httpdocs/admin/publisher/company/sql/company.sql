SELECT n.*,nc.name as category,

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

from company as n
	inner join company_category as nc
		on n.company_category_no = nc.company_category_no
WHERE n.company_no = '{$company_no|escape}'
	and n.publisher_no = '{$publisher_no|escape}';