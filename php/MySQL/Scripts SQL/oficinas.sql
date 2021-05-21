-- MySQL dump 10.13  Distrib 5.1.47, for redhat-linux-gnu (x86_64)
--
-- Host: localhost    Database: firewall
-- ------------------------------------------------------
-- Server version	5.1.47

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
-- Table structure for table `oficinas`
--

DROP TABLE IF EXISTS `oficinas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oficinas` (
  `oficina` varchar(20) DEFAULT NULL,
  `abrev` varchar(20) DEFAULT NULL,
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oficinas`
--

LOCK TABLES `oficinas` WRITE;
/*!40000 ALTER TABLE `oficinas` DISABLE KEYS */;
INSERT INTO oficinas VALUES ('Culiacan','CUL'),('Zapopan','ZAP'),('Iztapalapa','IZT'),('Obregon','COB'),('Mochis','MCH'),('Tijuana','TIJ'),('Guadalajara','GDL'),('Nogales','NOG'),('Monterrey','MTY'),('Mexico','MEX'),('SantaAna','STA'),,('NuevoLaredo','NLA'),('Queretaro','QUE'),('CanCun','CCN'),('Chihuahua','CHI'),('Tepic','TEP'),('Merida','MER'),('Mazatlan','MAZ'),('Mexicali','MXL'),('Puebla','PUE'),('Sistemas','SIS'),('Hermosillo','HLO'),('Transporte','TRA'),('Administracion','DFA'),('Direccion General','DG'),('Recursos Humanos','RH'),('Documentacion','DOC'),('Direccion Comercial','DC'),('Volvo Hermosillo','VHL'),('Volvo Culiacan','VCL'),('Direccion de Operaci','DO'),('Tecnologia Diesel','TD'),('Batuc Express','BTX'),('Villa Hermosa','VIL');
/*!40000 ALTER TABLE `oficinas` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-03-28 10:56:49
