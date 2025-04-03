
/*
    Database: yt_clone
    Table: channels
*/

-- 1. create table
    -- if a table with same name exists, delete it!
    -- create
DROP TABLE IF EXISTS `channels`;
CREATE TABLE IF NOT EXISTS `channels` (
    -- id, channel name, channel desc, channel created dt, channel owner/user, videos uploaded, 
    `channel_id` int NOT NULL AUTO_INCREMENT, -- 1, 2 ...
    `channel_name` varchar(50) NOT NULL, -- BiniTutor
    `channel_description` varchar(2000) DEFAULT NULL,
    `channel_created_at` date NOT NULL, -- yyyy-mm-dd
    `channel_owner_fk` int NOT NULL, -- 1000, 1001
    PRIMARY KEY (`channel_id`),
    FOREIGN KEY (`channel_owner_fk`) REFERENCES `users`(`user_id`)
);

-- 2. insert data
INSERT INTO `channels` ( `channel_name`, `channel_description`,
`channel_created_at`, `channel_owner_fk`) 
VALUES 
("BiniTutor", "This is my channel.", "2023-11-01", 1000),
("Lisa Life", "This is Lisa's channel.", "2022-06-01", 1001);

-- correction: remove video id fk
-- 1. under channel --> structure --> indexes --> drop channel_video_id_fk
ALTER TABLE `channels` DROP INDEX `channel_video_id_fk`;
-- 2. drop the column
ALTER TABLE `channels` DROP COLUMN channel_video_id_fk;


