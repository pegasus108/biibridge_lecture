select coalesce(max(author_no), 0) + 1 as next_no
from author;
