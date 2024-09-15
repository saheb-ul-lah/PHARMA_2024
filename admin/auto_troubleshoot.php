DELETE t1 
FROM pharma_journals t1
INNER JOIN pharma_journals t2 
WHERE 
    t1.id > t2.id 
    AND t1.journal_name = t2.journal_name 
    AND t1.issn = t2.issn 
    AND t1.editor_name = t2.editor_name 
    AND t1.volume_thumbnail = t2.volume_thumbnail;


SHOW CREATE TABLE pharma_content;

SHOW TABLE STATUS LIKE 'pharma_journals';

