-- Last edited: Antonio J. Garcia 2007-04-24
-- leave subqueries on a single line in order that table prefixes works
BEGIN;
INSERT INTO comment VALUES (1, 'faq', NULL, 'Demian Turner', 'demian@phpkitchen.com', 'http://seagullproject.org/', '192.168.1.1', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.7) Gecko/20060909 Firefox/1.5.0.7', '', 'comment', 0, 1, 'this is my comment', '2006-09-04 16:16:05');
INSERT INTO comment VALUES (2, 'faq', NULL, 'Demian Turner', 'demian@phpkitchen.com', 'http://seagullproject.org/', '192.168.1.1', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.7) Gecko/20060909 Firefox/1.5.0.7', '', 'comment', 0, 1, 'this is another comment', '2006-09-04 16:16:06');
COMMIT;
