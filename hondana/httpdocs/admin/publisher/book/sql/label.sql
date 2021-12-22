SELECT *
FROM label
WHERE label_no = '{$label_no|escape}'
	and publisher_no = '{$publisher_no|escape}';