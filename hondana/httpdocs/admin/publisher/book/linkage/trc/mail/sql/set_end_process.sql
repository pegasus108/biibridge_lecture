lock tables linkage_trc write;


UPDATE
	linkage_trc

SET
	status = 2,
	process_date = current_timestamp

WHERE
	status = 1
;


unlock tables;
