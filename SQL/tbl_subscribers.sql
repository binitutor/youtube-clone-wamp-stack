
/*
    Database: yt_clone
    Table: subscribers
*/

-- people subscribe to a channel
-- sub entry id, channel id, user id
-- 1. create table
    -- if a table with same name exists, delete it!
    -- create
DROP TABLE IF EXISTS `subscribers`;
CREATE TABLE IF NOT EXISTS `subscribers` (
    `sub_id` int NOT NULL AUTO_INCREMENT, -- 1, 2 ...    
    `channel_id_fk` int NOT NULL,   
    `user_id_fk` int NOT NULL,
    PRIMARY KEY (`sub_id`),
    FOREIGN KEY (`channel_id_fk`) REFERENCES `channels`(`channel_id`),
    FOREIGN KEY (`user_id_fk`) REFERENCES `users`(`user_id`)
);


-- 2. insert data
INSERT INTO `subscribers` ( `channel_id_fk`, `user_id_fk` ) 
VALUES 
(1, 1001), -- lisa subscribe to my channel
(2, 1000); -- i subscribe to Lisa's channel




