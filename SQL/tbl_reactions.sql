
/*
    Database: yt_clone
    Table: reactions
*/

-- track who likes and dislikes the videos
-- entry id, video id, user id, like | dislike
-- 1. create table
    -- if a table with same name exists, delete it!
    -- create
DROP TABLE IF EXISTS `reactions`;
CREATE TABLE IF NOT EXISTS `reactions` (
    `react_id` int NOT NULL AUTO_INCREMENT, -- 1, 2 ...    
    `video_id_fk` int NOT NULL,   
    `user_id_fk` int NOT NULL, 
    `reaction` VARCHAR(10) NOT NULL CHECK (`reaction` IN ('like', 'dislike') ),
    PRIMARY KEY (`react_id`),
    FOREIGN KEY (`video_id_fk`) REFERENCES `videos`(`video_id`),
    FOREIGN KEY (`user_id_fk`) REFERENCES `users`(`user_id`)
);


-- 2. insert data
INSERT INTO `reactions` ( `video_id_fk`, `user_id_fk`, `reaction` ) 
VALUES 
(1, 1001, 'dislike'), -- lisa reacts to first video
(1, 1000, 'like'); -- i reacts to first video

-- 3. delete single row
DELETE FROM `reactions` WHERE `reactions`.`react_id` = 3

-- 4. remove all data from table
TRUNCATE TABLE `reactions`;