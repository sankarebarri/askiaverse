-- Add orbs column to user_progress table
-- Run this SQL to update the existing database

ALTER TABLE `user_progress` 
ADD COLUMN `orbs` int(11) DEFAULT 0 AFTER `experience`;

-- Update existing records to have 0 orbs
UPDATE `user_progress` SET `orbs` = 0 WHERE `orbs` IS NULL;
