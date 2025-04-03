
/*
    Database: yt_clone
    Table: com_reactions
*/

-- track who likes and dislikes the videos
-- entry id, video id, user id, like | dislike
-- 1. create table
    -- if a table with same name exists, delete it!
    -- create
DROP TABLE IF EXISTS `com_reactions`;
CREATE TABLE IF NOT EXISTS `com_reactions` (
    `com_react_id` int NOT NULL AUTO_INCREMENT, -- 1, 2 ...    
    `video_id_fk` int NOT NULL,   
    `user_id_fk` int NOT NULL,    
    `comment_id_fk` int NOT NULL, 
    `reaction` VARCHAR(10) NOT NULL CHECK (`reaction` IN ('like', 'dislike') ),
    PRIMARY KEY (`com_react_id`),
    FOREIGN KEY (`video_id_fk`) REFERENCES `videos`(`video_id`),
    FOREIGN KEY (`user_id_fk`) REFERENCES `users`(`user_id`),
    FOREIGN KEY (`comment_id_fk`) REFERENCES `comments`(`comment_id`)
);


-- 2. insert data
INSERT INTO `com_reactions` ( `video_id_fk`, `user_id_fk`, `comment_id_fk`, `reaction` ) 
VALUES 
(1, 1001, 2, 'dislike'), -- lisa dislikes my positive comment on first video
('1', '1000', '2', 'like'), 
('1', '1000', '1', 'dislike');

-- 3. remove all data
TRUNCATE TABLE `com_reactions`; -- remove all data from table