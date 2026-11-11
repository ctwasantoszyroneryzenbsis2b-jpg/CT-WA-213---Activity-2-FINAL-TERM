-- CampusConnect course schedule migration for an EXISTING campusconnect database.
-- Back up the database before importing. This does not drop users, courses, or enrollments.
USE campusconnect;
SET @db := DATABASE();
SET @q := IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='courses' AND COLUMN_NAME='schedule_day')=0,'ALTER TABLE courses ADD COLUMN schedule_day VARCHAR(15) DEFAULT NULL','SELECT "schedule_day already exists"'); PREPARE s FROM @q; EXECUTE s; DEALLOCATE PREPARE s;
SET @q := IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='courses' AND COLUMN_NAME='start_time')=0,'ALTER TABLE courses ADD COLUMN start_time TIME DEFAULT NULL','SELECT "start_time already exists"'); PREPARE s FROM @q; EXECUTE s; DEALLOCATE PREPARE s;
SET @q := IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='courses' AND COLUMN_NAME='end_time')=0,'ALTER TABLE courses ADD COLUMN end_time TIME DEFAULT NULL','SELECT "end_time already exists"'); PREPARE s FROM @q; EXECUTE s; DEALLOCATE PREPARE s;
SET @q := IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='courses' AND COLUMN_NAME='room')=0,'ALTER TABLE courses ADD COLUMN room VARCHAR(100) DEFAULT NULL','SELECT "room already exists"'); PREPARE s FROM @q; EXECUTE s; DEALLOCATE PREPARE s;
UPDATE courses SET schedule_day='Monday',start_time='08:00:00',end_time='10:00:00',room='Room 201' WHERE course_code='CC101' AND schedule_day IS NULL;
UPDATE courses SET schedule_day='Wednesday',start_time='10:00:00',end_time='12:00:00',room='Computer Lab' WHERE course_code='CC102' AND schedule_day IS NULL;
UPDATE courses SET schedule_day='Friday',start_time='13:00:00',end_time='15:00:00',room='Room 305' WHERE course_code='CC103' AND schedule_day IS NULL;
