CREATE DATABASE  IF NOT EXISTS `Neighbours` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `Neighbours`;
-- MySQL dump 10.13  Distrib 5.6.19, for osx10.7 (i386)
--
-- Host: localhost    Database: Neighbours
-- ------------------------------------------------------
-- Server version	5.7.10

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
-- Table structure for table `Appliances`
--

DROP TABLE IF EXISTS `Appliances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Appliances` (
  `idAppliances` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) DEFAULT NULL,
  `Power` int(11) DEFAULT NULL,
  `icon` varchar(45) DEFAULT NULL,
  `x` int(3) DEFAULT NULL,
  `y` int(3) DEFAULT NULL,
  PRIMARY KEY (`idAppliances`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Appliances`
--

LOCK TABLES `Appliances` WRITE;
/*!40000 ALTER TABLE `Appliances` DISABLE KEYS */;
INSERT INTO `Appliances` VALUES (1,'Immersion heater',3000,'immersion',220,90),(2,'Light bulb',60,'bulb',223,30),(3,'Electric drill',900,'power_tool',170,90),(4,'Fan heater',1200,'fan_heater',290,90),(5,'Storage heater',2500,'heater',290,90),(10,'BEDROOM',0,'0',999,777),(11,'Light bulb',60,'bulb',150,150),(12,'Spot lights',50,'spot',190,150),(13,'LEDs',8,'led',110,150),(14,'TV',170,'TV',100,180),(15,'Low energy light',12,'cfd',150,180),(16,'HiFi',1200,'radio',190,180),(17,'iPad',4,'tablet',150,180),(18,'Fish tank',80,'',100,180),(19,'Fan heater',1200,'fan_heater',200,210),(20,'Storage heater',2500,'heater',200,210),(21,'Vaccum cleaner',1200,'vacuum_cleaner',100,210),(30,'BATHROOM',0,'0',999,777),(31,'Light bulb',60,'bulb',300,150),(32,'Spot lights',50,'spot',350,150),(33,'LEDs',8,'led',250,150),(34,'Hairdryer',2000,'hair_dryer',250,180),(35,'Hair curler',40,'hair_curler',290,180),(36,'Hair straighteners',40,'hair_curler',340,180),(38,'Electric toothbrush',40,'toothbrush',340,180),(39,'Fan heater',1200,'fan_heater',350,180),(40,'Storage heater',2500,'heater',350,180),(50,'KITCHEN',0,'0',999,255),(51,'Light bulb',60,'bulb',250,255),(52,'Spot lights',50,'spot',300,255),(53,'LEDs',8,'led',350,255),(55,'Kettle',2200,'kettle',320,270),(56,'Oven',2200,'oven',340,320),(57,'Hob',1400,'hob',340,290),(58,'Microwave',800,'microwave',350,270),(59,'Fridge',120,'fridge',290,280),(61,'Dishwasher',1050,'dishwasher',290,320),(62,'Washing machine',2000,'washing_machine',250,320),(63,'Tumble dryer',2000,'tumble_dryer',250,280),(70,'LIVING ROOM',0,'0',999,777),(71,'Light bulb',60,'bulb',150,255),(72,'Spot lights',50,'spot',110,255),(73,'LEDs',8,'led',190,255),(74,'HiFi',1200,'radio',200,280),(75,'TV',170,'TV',100,280),(76,'DVD + TV',140,'DVD',100,280),(78,'Games console',45,'games',150,280),(79,'Radio',40,'radio',200,280),(80,'Record player',45,'records',200,280),(81,'Laptop',50,'laptop',200,280),(82,'Broadband router',10,'router',100,320),(83,'PC',120,'computer',200,280),(84,'Printer (stand by)',20,'printer',150,320),(87,'Telephone',20,'mobile',200,320),(88,'Mobile phone charger',3,'mobile',200,320),(89,'Fan heater',1200,'fan_heater',100,320),(90,'Storage heater',2500,'heater',100,320),(91,'Electric blanket',130,'blanket',100,320),(99,'Electric car charger',4000,'ev',40,280);
/*!40000 ALTER TABLE `Appliances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Choices`
--

DROP TABLE IF EXISTS `Choices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Choices` (
  `Street_idStreet` int(11) DEFAULT NULL,
  `House_idHouse` int(11) DEFAULT NULL,
  `Appliance_idAppliance` int(11) DEFAULT NULL,
  `Minutes` int(11) DEFAULT NULL,
  `House_Round` int(11) DEFAULT '0',
  `idChoices` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`idChoices`)
) ENGINE=InnoDB AUTO_INCREMENT=75164 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Choices`
--

LOCK TABLES `Choices` WRITE;
/*!40000 ALTER TABLE `Choices` DISABLE KEYS */;
INSERT INTO `Choices` VALUES (0,1,1,60,0,1),(0,1,2,60,0,2),(0,1,3,60,0,3),(0,1,4,60,0,4),(0,1,5,60,0,5),(0,1,11,60,0,6),(0,1,12,60,0,7),(0,1,13,60,0,8),(0,1,14,60,0,9),(0,1,15,60,0,10),(0,1,16,60,0,11),(0,1,17,60,0,12),(0,1,18,60,0,13),(0,1,19,60,0,14),(0,1,20,60,0,15),(0,1,31,60,0,16),(0,1,32,60,0,17),(0,1,33,60,0,18),(0,1,34,60,0,19),(0,1,35,60,0,20),(0,1,36,60,0,21),(0,1,37,60,0,22),(0,1,38,60,0,23),(0,1,39,60,0,24),(0,1,40,60,0,25),(0,1,51,60,0,26),(0,1,52,60,0,27),(0,1,53,60,0,28),(0,1,55,60,0,29),(0,1,56,60,0,30),(0,1,57,60,0,31),(0,1,58,60,0,32),(0,1,59,60,0,33),(0,1,61,60,0,34),(0,1,62,60,0,35),(0,1,63,60,0,36),(0,1,71,60,0,37),(0,1,72,60,0,38),(0,1,73,60,0,39),(0,1,74,60,0,40),(0,1,75,60,0,41),(0,1,76,60,0,42),(0,1,78,60,0,43),(0,1,79,60,0,44),(0,1,80,60,0,45),(0,1,81,60,0,46),(0,1,82,60,0,47),(0,1,83,60,0,48),(0,1,84,60,0,49),(0,1,87,60,0,50),(0,1,88,60,0,51),(0,1,89,60,0,52),(0,1,90,60,0,53),(0,1,91,60,0,54),(0,1,99,60,0,55),(0,2,1,60,0,56),(0,2,2,60,0,57),(0,2,4,60,0,58),(0,2,11,60,0,59),(0,2,19,60,0,60),(0,2,31,60,0,61),(0,2,51,30,0,62),(0,2,55,10,0,63),(0,2,56,30,0,64),(0,2,57,10,0,65),(0,2,59,60,0,66),(0,2,62,20,0,67),(0,2,71,60,0,68),(0,2,72,60,0,69),(0,2,80,60,0,70),(0,2,75,60,0,71),(0,2,89,60,0,72),(0,3,2,60,0,73),(0,3,3,5,0,74),(0,3,13,60,0,75),(0,3,14,60,0,76),(0,3,16,60,0,77),(0,3,17,60,0,78),(0,3,33,60,0,79),(0,3,34,20,0,80),(0,3,35,20,0,81),(0,3,38,20,0,82),(0,3,51,30,0,83),(0,3,52,30,0,84),(0,3,55,10,0,85),(0,3,58,10,0,86),(0,3,59,60,0,87),(0,3,61,60,0,88),(0,3,63,20,0,89),(0,3,71,60,0,90),(0,3,72,60,0,91),(0,3,73,60,0,92),(0,3,75,60,0,93),(0,3,78,60,0,94),(0,3,81,60,0,95),(0,3,82,60,0,96),(0,3,84,60,0,97),(0,3,88,60,0,98),(0,3,99,60,0,99),(0,4,1,30,0,100),(0,4,11,60,0,101),(0,4,12,60,0,102),(0,4,14,60,0,103),(0,4,17,60,0,104),(0,4,21,30,0,105),(0,4,32,60,0,106),(0,4,33,30,0,107),(0,4,34,10,0,108),(0,4,38,4,0,109),(0,4,52,30,0,110),(0,4,53,60,0,111),(0,4,55,10,0,112),(0,4,56,30,0,113),(0,4,57,20,0,114),(0,4,59,60,0,115),(0,4,60,60,0,116),(0,4,61,60,0,117),(0,4,62,30,0,118),(0,4,63,30,0,119),(0,4,72,60,0,120),(0,4,73,60,0,121),(0,4,76,60,0,122),(0,4,88,60,0,123),(0,5,2,60,0,124),(0,5,11,60,0,125),(0,5,14,60,0,126),(0,5,17,60,0,127),(0,5,31,60,0,128),(0,5,35,15,0,129),(0,5,38,5,0,130),(0,5,51,60,0,131),(0,5,52,60,0,132),(0,5,55,10,0,133),(0,5,57,5,0,134),(0,5,58,12,0,135),(0,5,59,60,0,136),(0,5,62,30,0,137),(0,5,71,60,0,138),(0,5,72,60,0,139),(0,5,76,60,0,140),(0,5,78,20,0,141),(0,5,81,60,0,142),(0,5,88,10,0,143);
/*!40000 ALTER TABLE `Choices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `House`
--

DROP TABLE IF EXISTS `House`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `House` (
  `idHouse` int(11) NOT NULL AUTO_INCREMENT,
  `Street_idStreet` int(11) DEFAULT NULL,
  `HouseType` int(11) DEFAULT NULL,
  `Round` int(3) DEFAULT '0',
  PRIMARY KEY (`idHouse`)
) ENGINE=InnoDB AUTO_INCREMENT=445 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `House`
--

LOCK TABLES `House` WRITE;
/*!40000 ALTER TABLE `House` DISABLE KEYS */;
INSERT INTO `House` VALUES (1,1,1,0);
/*!40000 ALTER TABLE `House` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Round`
--

DROP TABLE IF EXISTS `Round`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Round` (
  `idRound` int(11) NOT NULL AUTO_INCREMENT,
  `Street_idStreet` int(11) DEFAULT NULL,
  `Street_Round` int(11) DEFAULT NULL,
  `result` float DEFAULT NULL,
  `target` float DEFAULT NULL,
  PRIMARY KEY (`idRound`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Round`
--

LOCK TABLES `Round` WRITE;
/*!40000 ALTER TABLE `Round` DISABLE KEYS */;
INSERT INTO `Round` VALUES (1,1,1,1,1);
/*!40000 ALTER TABLE `Round` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Story`
--

DROP TABLE IF EXISTS `Story`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Story` (
  `Street_House_Round` int(3) NOT NULL,
  `House_HouseType` int(3) NOT NULL,
  `Message` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`Street_House_Round`,`House_HouseType`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Story`
--

LOCK TABLES `Story` WRITE;
/*!40000 ALTER TABLE `Story` DISABLE KEYS */;
INSERT INTO `Story` VALUES (0,1,'Look at all the stuff we used yesterday. Do we really need all that to be on? Click to see what they are and to change their use.'),(0,2,'Darling,<br>Look at all the stuff we used yesterday. Do we really need all that to be on? Click to see what they are and to switch off what you do not really need.'),(0,3,'Yesterday was amazing.</br>We got to level 12. </br>Let\'s do another round of multiplayer gaming tonight! '),(0,4,'I could eat roast every day. This should be done in an hour - just the time I need to clean the house.'),(0,5,'Yesterday we had so much fun. Look how much was going on. Let\'s do all that again today.'),(1,2,'</br>Darling,<br><br>I\'m feeling a bit chilly. What shall we do?'),(1,3,'Oh, no.</br>All our devices are low on charge.</br></br> Quick, avoid desaster.'),(1,4,'Everyone is happy, so long as my daughter can do her hair, the boys have TV and I... well time for a roast, I think.'),(1,5,'Guys, I had enough of por noodles - let\'s order in a pizza, play games and watch somthing.'),(2,2,'</br>Darling,<br>Tonight is Stricktly. Let\'s cook somthing nice, watch TV and have some music....'),(2,3,'Yeah. We are having a LAN party. It is going to be full house!'),(2,4,'Oh, no. My mother-in-law will be here in one hour. Quick, cook, clean the house. Only one minute to decide this time.'),(2,5,'Party time. Everyone is comming. Quick, beer into the fridge, music on the computer and we\'ll have some fun!');
/*!40000 ALTER TABLE `Story` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Street`
--

DROP TABLE IF EXISTS `Street`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Street` (
  `idStreet` int(11) NOT NULL AUTO_INCREMENT,
  `StreetName` varchar(45) DEFAULT NULL,
  `started` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `House_Round` int(11) DEFAULT '0',
  `Power_Points` int(11) DEFAULT NULL,
  PRIMARY KEY (`idStreet`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Street`
--

LOCK TABLES `Street` WRITE;
/*!40000 ALTER TABLE `Street` DISABLE KEYS */;
INSERT INTO `Street` VALUES (0,'Default','2016-10-01 14:17:45',NULL,NULL),(1,'Test','2016-10-01 14:17:45',NULL,NULL);
/*!40000 ALTER TABLE `Street` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-10-24  9:05:06
