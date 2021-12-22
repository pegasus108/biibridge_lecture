SELECT n.*,

date_format(
	n.price_effective_from
,'%Y') as price_effective_from_year,

date_format(
	n.price_effective_from
,'%m') as price_effective_from_month,

date_format(
	n.price_effective_from
,'%d') as price_effective_from_day,


date_format(
	n.price_effective_until
,'%Y') as price_effective_until_year,

date_format(
	n.price_effective_until
,'%m') as price_effective_until_month,

date_format(
	n.price_effective_until
,'%d') as price_effective_until_day,


date_format(
	n.on_sale_date
,'%Y') as on_sale_date_year,

date_format(
	n.on_sale_date
,'%m') as on_sale_date_month,

date_format(
	n.on_sale_date
,'%d') as on_sale_date_day,


date_format(
	n.pre_order_limit
,'%Y') as pre_order_limit_year,

date_format(
	n.pre_order_limit
,'%m') as pre_order_limit_month,

date_format(
	n.pre_order_limit
,'%d') as pre_order_limit_day,


date_format(
	n.announcement_date
,'%Y') as announcement_date_year,

date_format(
	n.announcement_date
,'%m') as announcement_date_month,

date_format(
	n.announcement_date
,'%d') as announcement_date_day,


date_format(
	n.recent_publication_date
,'%Y') as recent_publication_date_year,

date_format(
	n.recent_publication_date
,'%m') as recent_publication_date_month,

date_format(
	n.recent_publication_date
,'%d') as recent_publication_date_day,


date_format(
	n.reselling_date
,'%Y') as reselling_date_year,

date_format(
	n.reselling_date
,'%m') as reselling_date_month,

date_format(
	n.reselling_date
,'%d') as reselling_date_day,

date_format(
	n.Issued_date
,'%Y') as Issued_date_year,

date_format(
	n.Issued_date
,'%m') as Issued_date_month,

date_format(
	n.Issued_date
,'%d') as Issued_date_day,

date_format(
	n.delivery_date
,'%Y') as delivery_date_year,

date_format(
	n.delivery_date
,'%m') as delivery_date_month,

date_format(
	n.delivery_date
,'%d') as delivery_date_day,

date_format(
	n.return_deadline
,'%Y') as return_deadline_year,

date_format(
	n.return_deadline
,'%m') as return_deadline_month,

date_format(
	n.return_deadline
,'%d') as return_deadline_day


from book_jpo as n
WHERE n.book_no = {$book_no};
