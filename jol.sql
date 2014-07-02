-- MySQL dump 10.13  Distrib 5.5.37, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: jol
-- ------------------------------------------------------
-- Server version	5.5.37-0ubuntu0.12.10.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `attend`
--

DROP TABLE IF EXISTS `attend`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attend` (
  `user_id` char(20) NOT NULL DEFAULT '',
  `contest_id` int(11) NOT NULL DEFAULT '0',
  `accepts` int(11) DEFAULT '0',
  `penalty` int(11) DEFAULT '0',
  `A_time` int(11) DEFAULT '0',
  `A_WrongSubmits` int(11) DEFAULT '0',
  `B_time` int(11) DEFAULT '0',
  `B_WrongSubmits` int(11) DEFAULT '0',
  `C_time` int(11) DEFAULT '0',
  `C_WrongSubmits` int(11) DEFAULT '0',
  `D_time` int(11) DEFAULT '0',
  `D_WrongSubmits` int(11) DEFAULT '0',
  `E_time` int(11) DEFAULT '0',
  `E_WrongSubmits` int(11) DEFAULT '0',
  `F_time` int(11) DEFAULT '0',
  `F_WrongSubmits` int(11) DEFAULT '0',
  `G_time` int(11) DEFAULT '0',
  `G_WrongSubmits` int(11) DEFAULT '0',
  `H_time` int(11) DEFAULT '0',
  `H_WrongSubmits` int(11) DEFAULT '0',
  `I_time` int(11) DEFAULT '0',
  `I_WrongSubmits` int(11) DEFAULT '0',
  `J_time` int(11) DEFAULT '0',
  `J_WrongSubmits` int(11) DEFAULT '0',
  `K_time` int(11) DEFAULT '0',
  `K_WrongSubmits` int(11) DEFAULT '0',
  `L_time` int(11) DEFAULT '0',
  `L_WrongSubmits` int(11) DEFAULT '0',
  `M_time` int(11) DEFAULT '0',
  `M_WrongSubmits` int(11) DEFAULT '0',
  `N_time` int(11) DEFAULT '0',
  `N_WrongSubmits` int(11) DEFAULT '0',
  `O_time` int(11) DEFAULT '0',
  `O_WrongSubmits` int(11) DEFAULT '0',
  `P_time` int(11) DEFAULT '0',
  `P_WrongSubmits` int(11) DEFAULT '0',
  `Q_time` int(11) DEFAULT '0',
  `Q_WrongSubmits` int(11) DEFAULT '0',
  `R_time` int(11) DEFAULT '0',
  `R_WrongSubmits` int(11) DEFAULT '0',
  `S_time` int(11) DEFAULT '0',
  `S_WrongSubmits` int(11) DEFAULT '0',
  `T_time` int(11) DEFAULT '0',
  `T_WrongSubmits` int(11) DEFAULT '0',
  `U_time` int(11) DEFAULT '0',
  `U_WrongSubmits` int(11) DEFAULT '0',
  `V_time` int(11) DEFAULT '0',
  `V_WrongSubmits` int(11) DEFAULT '0',
  `W_time` int(11) DEFAULT '0',
  `W_WrongSubmits` int(11) DEFAULT '0',
  `X_time` int(11) DEFAULT '0',
  `X_WrongSubmits` int(11) DEFAULT '0',
  `Y_time` int(11) DEFAULT '0',
  `Y_WrongSubmits` int(11) DEFAULT '0',
  `Z_time` int(11) DEFAULT '0',
  `Z_WrongSubmits` int(11) DEFAULT '0',
  `nick` char(100) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bm_contest`
--

DROP TABLE IF EXISTS `bm_contest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bm_contest` (
  `bm_c_id` int(11) NOT NULL AUTO_INCREMENT,
  `bm_c_title` varchar(255) DEFAULT NULL,
  `bm_c_start` datetime DEFAULT NULL,
  `bm_c_end` datetime DEFAULT NULL,
  `bm_c_startreg` datetime DEFAULT NULL,
  `bm_c_endreg` datetime DEFAULT NULL,
  `bm_c_defunct` char(1) NOT NULL DEFAULT 'N',
  `bm_c_kind` char(1) NOT NULL DEFAULT 'N',
  `bm_c_description` text,
  PRIMARY KEY (`bm_c_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bm_stu`
--

DROP TABLE IF EXISTS `bm_stu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bm_stu` (
  `bm_s_id` int(11) NOT NULL AUTO_INCREMENT,
  `bm_s_number` varchar(255) DEFAULT NULL,
  `bm_s_name` varchar(255) DEFAULT NULL,
  `bm_s_school` varchar(255) DEFAULT NULL,
  `bm_s_class` varchar(255) DEFAULT NULL,
  `bm_s_phone` varchar(255) DEFAULT NULL,
  `bm_s_mail` varchar(255) DEFAULT NULL,
  `bm_s_sex` char(1) NOT NULL DEFAULT 'N',
  `bm_s_codelang` varchar(255) DEFAULT 'C++',
  `bm_t_id` int(11) NOT NULL,
  `bm_s_123` int(11) NOT NULL,
  PRIMARY KEY (`bm_s_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bm_team`
--

DROP TABLE IF EXISTS `bm_team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bm_team` (
  `bm_t_id` int(11) NOT NULL AUTO_INCREMENT,
  `bm_t_name` varchar(255) DEFAULT NULL,
  `bm_c_id` int(11) NOT NULL,
  `bm_t_ojid` varchar(255) DEFAULT NULL,
  `bm_t_coach` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`bm_t_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `compileinfo`
--

DROP TABLE IF EXISTS `compileinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compileinfo` (
  `solution_id` int(11) NOT NULL DEFAULT '0',
  `error` text,
  PRIMARY KEY (`solution_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contest`
--

DROP TABLE IF EXISTS `contest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contest` (
  `contest_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `defunct` char(1) NOT NULL DEFAULT 'N',
  `description` text,
  `private` tinyint(4) NOT NULL DEFAULT '0',
  `langmask` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'bits for LANG to mask',
  `reg_start_time` datetime DEFAULT NULL,
  `reg_end_time` datetime DEFAULT NULL,
  PRIMARY KEY (`contest_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1458 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contest_problem`
--

DROP TABLE IF EXISTS `contest_problem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contest_problem` (
  `problem_id` int(11) NOT NULL DEFAULT '0',
  `contest_id` int(11) DEFAULT NULL,
  `title` char(200) NOT NULL DEFAULT '',
  `num` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `loginlog`
--

DROP TABLE IF EXISTS `loginlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loginlog` (
  `user_id` varchar(20) NOT NULL DEFAULT '',
  `password` blob,
  `ip` varchar(100) DEFAULT NULL,
  `time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mail`
--

DROP TABLE IF EXISTS `mail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mail` (
  `mail_id` int(11) NOT NULL AUTO_INCREMENT,
  `to_user` varchar(20) NOT NULL DEFAULT '',
  `from_user` varchar(20) NOT NULL DEFAULT '',
  `title` varchar(200) NOT NULL DEFAULT '',
  `content` text,
  `new_mail` tinyint(1) NOT NULL DEFAULT '1',
  `reply` tinyint(4) DEFAULT '0',
  `in_date` datetime DEFAULT NULL,
  `defunct` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`mail_id`),
  KEY `uid` (`to_user`)
) ENGINE=MyISAM AUTO_INCREMENT=1262 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message` (
  `message_id` int(11) NOT NULL DEFAULT '0',
  `problem_id` int(11) NOT NULL DEFAULT '0',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `thread_id` int(11) NOT NULL DEFAULT '0',
  `depth` int(11) NOT NULL DEFAULT '0',
  `orderNum` int(11) NOT NULL DEFAULT '0',
  `user_id` varchar(20) NOT NULL DEFAULT '',
  `title` varchar(200) NOT NULL DEFAULT '',
  `content` text,
  `in_date` datetime DEFAULT NULL,
  `defunct` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`message_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `news_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(20) NOT NULL DEFAULT '',
  `title` varchar(200) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `importance` tinyint(4) NOT NULL DEFAULT '0',
  `defunct` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`news_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `online`
--

DROP TABLE IF EXISTS `online`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `online` (
  `hash` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `ua` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `refer` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastmove` int(10) NOT NULL,
  `firsttime` int(10) DEFAULT NULL,
  `uri` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`hash`),
  UNIQUE KEY `hash` (`hash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `privilege`
--

DROP TABLE IF EXISTS `privilege`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `privilege` (
  `user_id` char(20) NOT NULL DEFAULT '',
  `rightstr` char(30) NOT NULL DEFAULT '',
  `defunct` char(1) NOT NULL DEFAULT 'N'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `problem`
--

DROP TABLE IF EXISTS `problem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `problem` (
  `problem_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL DEFAULT '',
  `description` text,
  `input` text,
  `output` text,
  `input_path` varchar(255) DEFAULT NULL,
  `output_path` varchar(255) DEFAULT NULL,
  `sample_input` text,
  `sample_output` text,
  `hint` text,
  `source` varchar(100) DEFAULT NULL,
  `in_date` datetime DEFAULT NULL,
  `time_limit` int(11) NOT NULL DEFAULT '0',
  `memory_limit` int(11) NOT NULL DEFAULT '0',
  `defunct` char(1) NOT NULL DEFAULT 'N',
  `contest_id` int(11) DEFAULT NULL,
  `accepted` int(11) DEFAULT '0',
  `submit` int(11) DEFAULT '0',
  `solved` int(11) DEFAULT '0',
  `spj` tinyint(4) NOT NULL DEFAULT '0',
  `author` varchar(48) DEFAULT '',
  PRIMARY KEY (`problem_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3600 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reply`
--

DROP TABLE IF EXISTS `reply`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reply` (
  `rid` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` varchar(20) NOT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `content` text NOT NULL,
  `topic_id` int(11) NOT NULL,
  `status` int(2) NOT NULL DEFAULT '0',
  `ip` varchar(30) NOT NULL,
  PRIMARY KEY (`rid`),
  KEY `author_id` (`author_id`)
) ENGINE=MyISAM AUTO_INCREMENT=474 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `runtimeinfo`
--

DROP TABLE IF EXISTS `runtimeinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `runtimeinfo` (
  `solution_id` int(11) NOT NULL DEFAULT '0',
  `error` text,
  PRIMARY KEY (`solution_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sim`
--

DROP TABLE IF EXISTS `sim`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sim` (
  `s_id` int(11) NOT NULL,
  `sim_s_id` int(11) DEFAULT NULL,
  `sim` int(11) DEFAULT NULL,
  PRIMARY KEY (`s_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `solution`
--

DROP TABLE IF EXISTS `solution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solution` (
  `solution_id` int(11) NOT NULL AUTO_INCREMENT,
  `problem_id` int(11) NOT NULL DEFAULT '0',
  `user_id` char(20) NOT NULL,
  `time` int(11) NOT NULL DEFAULT '0',
  `memory` int(11) NOT NULL DEFAULT '0',
  `in_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `result` smallint(6) NOT NULL DEFAULT '0',
  `language` tinyint(4) NOT NULL DEFAULT '0',
  `ip` char(15) NOT NULL,
  `contest_id` int(11) DEFAULT NULL,
  `valid` tinyint(4) NOT NULL DEFAULT '1',
  `num` tinyint(4) NOT NULL DEFAULT '-1',
  `code_length` int(11) NOT NULL DEFAULT '0',
  `judgetime` datetime DEFAULT NULL,
  PRIMARY KEY (`solution_id`),
  KEY `uid` (`user_id`),
  KEY `pid` (`problem_id`),
  KEY `res` (`result`),
  KEY `cid` (`contest_id`)
) ENGINE=MyISAM AUTO_INCREMENT=345310 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `source_code`
--

DROP TABLE IF EXISTS `source_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `source_code` (
  `solution_id` int(11) NOT NULL DEFAULT '0',
  `source` text CHARACTER SET utf8,
  PRIMARY KEY (`solution_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `topic`
--

DROP TABLE IF EXISTS `topic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `topic` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varbinary(60) NOT NULL,
  `status` int(2) NOT NULL DEFAULT '0',
  `top_level` int(2) NOT NULL DEFAULT '0',
  `cid` int(11) DEFAULT NULL,
  `pid` int(11) NOT NULL,
  `author_id` varchar(20) NOT NULL,
  PRIMARY KEY (`tid`),
  KEY `cid` (`cid`,`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=313 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` varchar(20) NOT NULL DEFAULT '',
  `email` varchar(100) DEFAULT NULL,
  `submit` int(11) DEFAULT '0',
  `solved` int(11) DEFAULT '0',
  `defunct` char(1) NOT NULL DEFAULT 'N',
  `ip` varchar(20) NOT NULL DEFAULT '',
  `accesstime` datetime DEFAULT NULL,
  `volume` int(11) NOT NULL DEFAULT '1',
  `language` int(11) NOT NULL DEFAULT '1',
  `password` varchar(32) NOT NULL,
  `reg_time` datetime DEFAULT NULL,
  `nick` varchar(100) NOT NULL DEFAULT '',
  `school` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `contestreg`;
CREATE TABLE `contestreg` (
  `user_id` varchar(20) NOT NULL DEFAULT '',
  `contest_id` int(11) NOT NULL DEFAULT '0',
  `sturealname` varchar(100) NOT NULL DEFAULT '',
  `stuid` varchar(20) NOT NULL DEFAULT '',
  `stusex` char(1) NOT NULL DEFAULT 'M',
  `stuphone` varchar(50) DEFAULT NULL,
  `stuemail` varchar(50) DEFAULT NULL,
  `stuschoolname` varchar(100) DEFAULT NULL,
  `studepartment` varchar(100) DEFAULT NULL,
  `stumajor` varchar(100) DEFAULT NULL,
  `ispending` tinyint(4) NOT NULL DEFAULT '0',
  `registertime` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`,`contest_id`),
  FOREIGN KEY (`user_id`) REFERENCES users(`user_id`),
  FOREIGN KEY (`contest_id`) REFERENCES users(`contest_id`)	
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-06-15 17:58:32
