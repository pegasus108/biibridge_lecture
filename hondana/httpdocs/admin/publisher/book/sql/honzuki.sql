{*lock tables book_honzuki write;*}

{foreach item=v from=$book_no_list}

    INSERT INTO
        book_honzuki(
            book_no,
            is_enable
        )
    VALUES(

        '{$v}' ,

        '1'

    )
    ON DUPLICATE KEY UPDATE
        book_no = VALUES(book_no),
        is_enable = VALUES(is_enable);

{/foreach}

{*unlock tables;*}

