-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 26, 2012 at 07:29 PM
-- Server version: 5.5.9
-- PHP Version: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `SmartRecruit`
--

-- --------------------------------------------------------

--
-- Table structure for table `COMPANY`
--

CREATE TABLE `COMPANY` (
  `COMPANY_ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `COMPANY_NAME` varchar(255) NOT NULL,
  `CREATE_TIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `MODIFY_TIMESTAMP` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ACTIVE` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`COMPANY_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=101 ;

-- --------------------------------------------------------

--
-- Table structure for table `INTERVIEW`
--

CREATE TABLE `INTERVIEW` (
  `INTERVIEW_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `JOB_ID` int(10) unsigned NOT NULL,
  `CREATE_TIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `MODIFY_TIMESTAMP` timestamp NULL DEFAULT NULL,
  `ACTIVE` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`INTERVIEW_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=413 ;

-- --------------------------------------------------------

--
-- Table structure for table `JOB`
--

CREATE TABLE `JOB` (
  `JOB_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `RECRUITER_ID` int(10) unsigned NOT NULL,
  `COMPANY_ID` int(10) unsigned NOT NULL,
  `LINKEDIN_JOB_ID` varchar(25) NOT NULL,
  `TITLE` varchar(255) NOT NULL,
  `INTERVIEW_ID` int(10) unsigned NOT NULL,
  `INTERVIEW_URL` varchar(255) NOT NULL,
  `CREATE_TIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `MODIFY_TIMESTAMP` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ACTIVE` smallint(6) NOT NULL,
  `LOCATION` varchar(256) NOT NULL,
  `DESCRIPTION` longtext NOT NULL,
  `IS_ACTIVE` smallint(6) NOT NULL DEFAULT '1' COMMENT '1 for active, 0 for inactive',
  `POSTING_DATE` date NOT NULL,
  `EXPIRATION_DATE` date NOT NULL,
  `POSTED_BY_LINKEDIN_ID` varchar(256) NOT NULL,
  PRIMARY KEY (`JOB_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `OBJECTIVE_CHOICES`
--

CREATE TABLE `OBJECTIVE_CHOICES` (
  `QUESTION_ID` int(10) unsigned NOT NULL,
  `CHOICE_ID` int(10) unsigned NOT NULL,
  `CHOICE_DESCRIPTION` varchar(255) NOT NULL,
  `CORRECT_CHOICE` int(1) DEFAULT '0',
  `CREATE_TIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `MODIFY_TIMESTAMP` timestamp NULL DEFAULT NULL,
  `ACTIVE` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `QUESTION`
--

CREATE TABLE `QUESTION` (
  `INTERVIEW_ID` int(10) unsigned NOT NULL,
  `STAGE_NO` int(10) unsigned NOT NULL,
  `QUESTION_ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `DESCRIPTION` varchar(255) NOT NULL,
  `QUESTION_TYPE` varchar(1) NOT NULL,
  `ANSWER_TYPE` varchar(1) NOT NULL,
  `ASSESSMENT_CRITERIA` varchar(255) DEFAULT NULL,
  `TIME_LIMIT` time NOT NULL,
  `CREATE_TIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `MODIFY_TIMESTAMP` timestamp NULL DEFAULT NULL,
  `ACTIVE` int(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`QUESTION_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=509 ;

-- --------------------------------------------------------

--
-- Table structure for table `QUESTION_BANK`
--

CREATE TABLE `QUESTION_BANK` (
  `RECRUITER_ID` int(10) NOT NULL,
  `QUESTION_ID` int(11) NOT NULL,
  KEY `RECRUITER_ID` (`RECRUITER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `RECRUITER`
--

CREATE TABLE `RECRUITER` (
  `RECRUITER_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `LINKEDIN_ID` varchar(25) NOT NULL,
  `FIRSTNAME` varchar(100) NOT NULL,
  `LASTNAME` varchar(100) NOT NULL,
  `CREATE_TIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `MODIFY_TIMESTAMP` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ACTIVE` smallint(6) NOT NULL DEFAULT '1' COMMENT '1 FOR ACTIVE; 0 FOR INACTIVE',
  PRIMARY KEY (`RECRUITER_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `RECRUITER_COMPANY`
--

CREATE TABLE `RECRUITER_COMPANY` (
  `RECRUITER_ID` int(11) unsigned NOT NULL,
  `COMPANY_ID` int(11) unsigned NOT NULL,
  `CREATE_TIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `MODIFY_TIMESTAMP` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ACTIVE` tinyint(4) NOT NULL,
  PRIMARY KEY (`RECRUITER_ID`,`COMPANY_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
