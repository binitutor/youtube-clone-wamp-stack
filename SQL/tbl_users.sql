
/*
    Database: yt_clone
    Table: users
*/

-- 1. create table
    -- if a table with same name exists, delete it!
    -- create
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
    -- col names and data type and size
    `user_id` int NOT NULL AUTO_INCREMENT, -- 1, 2 ...
    `user_name` varchar(50) NOT NULL, -- Full name
    `user_email` varchar(256) NOT NULL,
    `user_password` varchar(256) NOT NULL,
    `user_profile_url` varchar(500) DEFAULT NULL,
    `user_created_at` date NOT NULL, -- yyyy-mm-dd
    `user_updated_at` date DEFAULT NULL, -- yyyy-mm-dd
    PRIMARY KEY (`user_id`)
);

-- 2. insert data
INSERT INTO `users` (
    `user_name`, `user_email`, `user_password`,
    `user_profile_url`, `user_created_at`
) VALUES 
("Bini A.", "info@binitutor.com", "some-passwoord", 
"./uploads/users/main-profile.jpg", "2025-03-24"),
("Lisa Thomas", "lt@gmail.com", "some-passwoord", 
"./uploads/users/lisa.jpg", "2024-03-24");

-- 3. perform crud
    -- create new users (sign up)
    -- read/call the user profile to frontend
    -- update user info
    -- delete user from db

