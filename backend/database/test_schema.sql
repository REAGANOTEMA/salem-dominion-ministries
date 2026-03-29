-- SIMPLE TEST TO VERIFY TABLE CREATION
-- Run this first to test if basic table creation works

CREATE DATABASE IF NOT EXISTS salem_dominion_ministries CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE salem_dominion_ministries;

-- Drop existing test table
DROP TABLE IF EXISTS test_table;

-- Create a simple test table
CREATE TABLE test_table (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- Insert test data
INSERT INTO test_table (name) VALUES ('Test Data');

-- Verify test table exists
SHOW TABLES;

-- Check test table data
SELECT * FROM test_table;

-- If this works, then the issue is with the main schema
-- If this doesn't work, then there's a MySQL/connection issue
