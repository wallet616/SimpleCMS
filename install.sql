INSERT INTO `accounts` (`user_id`, `login`, `password`) VALUES ('1', 'john', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f');
INSERT INTO `categories` (`category_id`, `subcategory_of`, `name_pl`, `name_en`) VALUES
(1, 0, 'root', 'root'),
(8, 1, 'Ścisłe-i-przyrodnicze', 'Test-EN'),
(9, 8, 'matma', 't1'),
(10, 8, 'fizyka', 't2'),
(11, 10, 'termodynamika', 't3'),
(12, 1, 'polski', 't4'),
(13, 12, 'sienkiewicz', 't6'),
(14, 12, 'rejmont', 't7');
