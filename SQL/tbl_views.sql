
/*
    Database: yt_clone
    Table: views
    counts the views for each video
*/

-- 1. create table
    -- if a table with same name exists, delete it!
    -- create
DROP TABLE IF EXISTS `views`;
CREATE TABLE IF NOT EXISTS `views` (
    `view_entry` int NOT NULL AUTO_INCREMENT, -- 1, 2 ...
    `view_count` int NOT NULL,
    `video_id_fk` int NOT NULL,
    PRIMARY KEY (`view_entry`),
    FOREIGN KEY (`video_id_fk`) REFERENCES `videos`(`video_id`)
);

-- 2. insert data
INSERT INTO `views` ( `view_count`, `video_id_fk` ) VALUES 
(45, 1),
(200456, 2),
(3097313, 3);

