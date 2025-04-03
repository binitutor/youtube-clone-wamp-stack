
/*
    Database: yt_clone
    Table: replys
*/

-- people comment on video
-- comment id, video id, user id
-- 1. create table
    -- if a table with same name exists, delete it!
    -- create
DROP TABLE IF EXISTS `replys`;
CREATE TABLE IF NOT EXISTS `replys` (
    `replys_id` int NOT NULL AUTO_INCREMENT, -- 1, 2 ...    
    `video_id_fk` int NOT NULL,   
    `user_id_fk` int NOT NULL, 
    `comment_id_fk` int NOT NULL,  
    `reply` VARCHAR(2000) NOT NULL,
    PRIMARY KEY (`replys_id`),
    FOREIGN KEY (`video_id_fk`) REFERENCES `videos`(`video_id`),
    FOREIGN KEY (`user_id_fk`) REFERENCES `users`(`user_id`),
    FOREIGN KEY (`comment_id_fk`) REFERENCES `comments`(`comment_id`)
);


ALTER TABLE `replys`
ADD `replys_date` date DEFAULT NULL; -- yyyy-mm-dd

-- 2. insert data
INSERT INTO `replys` ( `video_id_fk`, `user_id_fk`, `comment_id_fk`, `reply` ) 
VALUES 
(1, 1001, 2, 'Not a good insight. You should not encourage this.'); -- lisa wrote negative reply to my comment on video




