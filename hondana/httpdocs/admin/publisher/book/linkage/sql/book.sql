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


left(n.magazine_code,5) as magazine_code_1,
right(n.magazine_code,2) as magazine_code_2


from book as n
WHERE n.book_no = {$book_no}
	and n.publisher_no = {$publisher_no};
