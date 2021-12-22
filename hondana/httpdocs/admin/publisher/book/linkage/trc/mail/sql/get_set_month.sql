SELECT
	max(
		date_format(set_date,'%m')
	) as `set_month`
FROM `linkage_trc;