
/*
    Database: yt_clone
    Table: comments
*/

-- people comment on video
-- comment id, video id, user id
-- 1. create table
    -- if a table with same name exists, delete it!
    -- create
DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
    `comment_id` int NOT NULL AUTO_INCREMENT, -- 1, 2 ...    
    `video_id_fk` int NOT NULL,   
    `user_id_fk` int NOT NULL,  
    `comment` VARCHAR(2000) NOT NULL,
    PRIMARY KEY (`comment_id`),
    FOREIGN KEY (`video_id_fk`) REFERENCES `videos`(`video_id`),
    FOREIGN KEY (`user_id_fk`) REFERENCES `users`(`user_id`)
);

-- add comment column
ALTER TABLE `comments`
ADD `comment` VARCHAR(2000) NOT NULL;

ALTER TABLE `comments`
ADD `comment_date` date DEFAULT NULL; -- yyyy-mm-dd

-- rename comment column as it is key word in MySQL database.
ALTER TABLE `comments` CHANGE `comment` comment_text varchar(2000);


-- 2. insert data
INSERT INTO `comments` ( `video_id_fk`, `user_id_fk`, `comment` ) 
VALUES 
(1, 1001, 'This is terrible. i did not like it!'), -- lisa wrote negative comment
(1, 1000, 'very empowering. The bible is a standard for life.'); -- i wrote positive comment




