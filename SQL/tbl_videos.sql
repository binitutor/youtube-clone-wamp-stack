
/*
    Database: yt_clone
    Table: videos
*/

-- 1. create table
    -- if a table with same name exists, delete it!
    -- create
DROP TABLE IF EXISTS `videos`;
CREATE TABLE IF NOT EXISTS `videos` (
    -- col names and data type and size
    `video_id` int NOT NULL AUTO_INCREMENT, -- 1, 2 ...
    `video_title` varchar(256) NOT NULL, -- video title
    `video_description` varchar(2000) DEFAULT NULL,
    `video_url` varchar(500) NOT NULL,
    `video_thumbnail_url` varchar(500) DEFAULT NULL,
    `video_duration` int DEFAULT NULL, -- in seconds: 10min = 60sec * 10 = 600 sec
    `video_created_at` date NOT NULL, -- yyyy-mm-dd
    `video_uploader_id_fk` int NOT NULL,
    `video_updated_at` date DEFAULT NULL, -- yyyy-mm-dd
    PRIMARY KEY (`video_id`),
    FOREIGN KEY (`video_uploader_id_fk`) REFERENCES `users`(`user_id`)
);

-- add tags column
ALTER TABLE `videos`
ADD `video_tags` varchar(500) DEFAULT NULL;

-- add channel id fk
ALTER TABLE `videos`
ADD `channel_id_fk` int DEFAULT NULL,
ADD CONSTRAINT FOREIGN KEY (`channel_id_fk`) REFERENCES `channels`(`channel_id`);



-- 2. insert data
INSERT INTO `videos` (
    `video_title`, `video_description`, `video_url`,
    `video_thumbnail_url`, `video_duration`, `video_created_at`,
    `video_uploader_id_fk`
) VALUES 
("Overview of the Entire Bible in 17 Minutes!", 
"The Bible is comprised of 66 different books written by about 40 authors over a period of about 1,500 years. It has nearly 1,200 chapters and over 30,000 verses. If you were to read a chapter each day, it would take over 3 years to read the entire Bible!", 
"./uploads/videos/bible.mp4", "./uploads/thumbnails/tmb-bible.png", 
18, "2025-03-24", 1000), 
("The Power Of Prayer: Making A Difference In Your Life", 
"Prayer is a cry, a conversation, and a confrontation. \n\n1. Prayer brings a REWARD.\n2. Prayer brings REVIVAL.\n3. Prayer brings RELEASE.\n4. Prayer brings RESULTS. \n\nGetting Practical About Prayer\n1. Proper Prayer: To the Father, through the Son, and by the H", 
"./uploads/videos/pray.mp4", "./uploads/thumbnails/tmb-pray.png", 
7, "2023-11-05", 1000), 
("Mali Music - I Believe", 
"Music video by Mali Music performing I Believe. (C) 2014 RCA Records, a division of Sony Music Entertainment", 
"./uploads/videos/sing.mp4", "./uploads/thumbnails/tmb-sing.png", 
4, "2022-06-19", 1001);

-- 3. perform crud
    -- create new video
    -- read/call the video to frontend
    -- update video info
    -- delete video from db

