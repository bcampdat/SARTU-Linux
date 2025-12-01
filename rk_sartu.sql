-- MySQL dump 10.13  Distrib 8.0.44, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: rk_sartu
-- ------------------------------------------------------
-- Server version	8.0.44

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `auditoria`
--

DROP TABLE IF EXISTS `auditoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auditoria` (
  `id_auditoria` bigint NOT NULL AUTO_INCREMENT,
  `accion` varchar(200) DEFAULT NULL,
  `entidad_tipo` varchar(100) DEFAULT NULL,
  `entidad_id` bigint DEFAULT NULL,
  `detalle` text,
  `datos_antes` json DEFAULT NULL,
  `datos_despues` json DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `user_agent` text,
  PRIMARY KEY (`id_auditoria`),
  KEY `auditoria_ibfk_1` (`user_id`),
  CONSTRAINT `auditoria_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_auditoria_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auditoria`
--

LOCK TABLES `auditoria` WRITE;
/*!40000 ALTER TABLE `auditoria` DISABLE KEYS */;
INSERT INTO `auditoria` VALUES (1,'login_correcto','Usuario',1,'Inicio de sesión correcto',NULL,NULL,'2025-11-28 08:15:30','2025-11-28 08:15:30','2025-11-28 08:15:30',1,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(2,'logout','Usuario',1,'Cierre de sesión',NULL,NULL,'2025-11-28 08:16:23','2025-11-28 08:16:23','2025-11-28 08:16:23',1,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(3,'login_correcto','Usuario',11,'Inicio de sesión correcto',NULL,NULL,'2025-11-28 08:16:36','2025-11-28 08:16:36','2025-11-28 08:16:36',11,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(4,'fichaje_entrada','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": null, \"lng\": null, \"tipo\": \"entrada\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 11}','2025-11-28 08:16:39','2025-11-28 08:16:39','2025-11-28 08:16:39',11,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(5,'fichaje_pausa','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": null, \"lng\": null, \"tipo\": \"pausa\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 11}','2025-11-28 08:24:18','2025-11-28 08:24:18','2025-11-28 08:24:18',11,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(6,'fichaje_reanudar','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": null, \"lng\": null, \"tipo\": \"reanudar\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 11}','2025-11-28 08:26:18','2025-11-28 08:26:18','2025-11-28 08:26:18',11,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(7,'fichaje_salida','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": null, \"lng\": null, \"tipo\": \"salida\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 11}','2025-11-28 08:26:32','2025-11-28 08:26:32','2025-11-28 08:26:32',11,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(8,'logout','Usuario',11,'Cierre de sesión',NULL,NULL,'2025-11-28 08:26:34','2025-11-28 08:26:34','2025-11-28 08:26:34',11,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(9,'login_fallido','Usuario',NULL,'Intento de acceso fallido',NULL,'{\"email\": \"empleado1@sartu.com\"}','2025-11-28 08:26:50','2025-11-28 08:26:50','2025-11-28 08:26:50',NULL,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(10,'login_correcto','Usuario',12,'Inicio de sesión correcto',NULL,NULL,'2025-11-28 08:27:07','2025-11-28 08:27:07','2025-11-28 08:27:07',12,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(11,'fichaje_entrada','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": null, \"lng\": null, \"tipo\": \"entrada\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 12}','2025-11-28 08:27:09','2025-11-28 08:27:09','2025-11-28 08:27:09',12,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(12,'fichaje_pausa','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": null, \"lng\": null, \"tipo\": \"pausa\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 12}','2025-11-28 08:28:10','2025-11-28 08:28:10','2025-11-28 08:28:10',12,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(13,'fichaje_reanudar','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": null, \"lng\": null, \"tipo\": \"reanudar\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 12}','2025-11-28 08:29:12','2025-11-28 08:29:12','2025-11-28 08:29:12',12,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(14,'fichaje_salida','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": null, \"lng\": null, \"tipo\": \"salida\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 12}','2025-11-28 08:29:18','2025-11-28 08:29:18','2025-11-28 08:29:18',12,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(15,'logout','Usuario',12,'Cierre de sesión',NULL,NULL,'2025-11-28 08:29:22','2025-11-28 08:29:22','2025-11-28 08:29:22',12,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(16,'login_correcto','Usuario',1,'Inicio de sesión correcto',NULL,NULL,'2025-11-28 08:35:56','2025-11-28 08:35:56','2025-11-28 08:35:56',1,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(17,'eliminar_usuario','Usuario',4,'Usuario eliminado','{\"id\": 4, \"name\": \"Encargado Pruebas\", \"email\": \"encargado@sartu.com\", \"activo\": 0, \"estado\": \"activo\", \"rol_id\": 2, \"empresa_id\": 1, \"fecha_registro\": \"2025-11-23T11:29:53.000000Z\", \"activation_token\": null, \"fecha_actualizacion\": \"2025-11-24T09:19:38.000000Z\"}',NULL,'2025-11-28 08:36:15','2025-11-28 08:36:15','2025-11-28 08:36:15',1,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(18,'eliminar_usuario','Usuario',10,'Usuario eliminado','{\"id\": 10, \"name\": \"Encargado Activo\", \"email\": \"enc_activo@sartu.com\", \"activo\": 1, \"estado\": \"activo\", \"rol_id\": 2, \"empresa_id\": 1, \"fecha_registro\": \"2025-11-23T12:22:08.000000Z\", \"activation_token\": null, \"fecha_actualizacion\": \"2025-11-24T09:53:30.000000Z\"}',NULL,'2025-11-28 08:36:32','2025-11-28 08:36:32','2025-11-28 08:36:32',1,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(19,'editar_usuario','Usuario',21,'Usuario actualizado','{\"id\": 21, \"name\": \"ametzjefe\", \"email\": \"ametz.jefe@sartu.com\", \"activo\": 1, \"estado\": \"activo\", \"rol_id\": 2, \"empresa_id\": 2, \"fecha_registro\": \"2025-11-27T13:09:29.000000Z\", \"activation_token\": null, \"fecha_actualizacion\": \"2025-11-27T13:12:31.000000Z\"}','{\"id\": 21, \"name\": \"Ametz Jefe\", \"email\": \"ametz.jefe@sartu.com\", \"activo\": \"1\", \"estado\": \"activo\", \"rol_id\": \"2\", \"empresa_id\": \"2\", \"fecha_registro\": \"2025-11-27T13:09:29.000000Z\", \"activation_token\": null, \"fecha_actualizacion\": \"2025-11-28T08:37:09.000000Z\"}','2025-11-28 08:37:09','2025-11-28 08:37:09','2025-11-28 08:37:09',1,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(20,'editar_usuario','Usuario',22,'Usuario actualizado','{\"id\": 22, \"name\": \"ametzempleado\", \"email\": \"ametz.empleado@sartu.com\", \"activo\": 1, \"estado\": \"activo\", \"rol_id\": 3, \"empresa_id\": 2, \"fecha_registro\": \"2025-11-27T13:10:52.000000Z\", \"activation_token\": null, \"fecha_actualizacion\": \"2025-11-27T13:12:31.000000Z\"}','{\"id\": 22, \"name\": \"Ametz Empleado\", \"email\": \"ametz.empleado@sartu.com\", \"activo\": \"1\", \"estado\": \"activo\", \"rol_id\": \"3\", \"empresa_id\": \"2\", \"fecha_registro\": \"2025-11-27T13:10:52.000000Z\", \"activation_token\": null, \"fecha_actualizacion\": \"2025-11-28T08:37:44.000000Z\"}','2025-11-28 08:37:44','2025-11-28 08:37:44','2025-11-28 08:37:44',1,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(21,'eliminar_usuario','Usuario',17,'Usuario eliminado','{\"id\": 17, \"name\": \"Aritz\", \"email\": \"aritz@sartu.com\", \"activo\": 2, \"estado\": \"activo\", \"rol_id\": 1, \"empresa_id\": 2, \"fecha_registro\": \"2025-11-27T12:49:14.000000Z\", \"activation_token\": null, \"fecha_actualizacion\": \"2025-11-27T12:53:38.000000Z\"}',NULL,'2025-11-28 08:37:53','2025-11-28 08:37:53','2025-11-28 08:37:53',1,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(22,'eliminar_usuario','Usuario',18,'Usuario eliminado','{\"id\": 18, \"name\": \"aritzEmpleado\", \"email\": \"aritz.empleado@sartu.com\", \"activo\": 1, \"estado\": \"activo\", \"rol_id\": 3, \"empresa_id\": null, \"fecha_registro\": \"2025-11-27T12:55:51.000000Z\", \"activation_token\": null, \"fecha_actualizacion\": \"2025-11-27T13:00:02.000000Z\"}',NULL,'2025-11-28 08:37:57','2025-11-28 08:37:57','2025-11-28 08:37:57',1,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(23,'eliminar_usuario','Usuario',19,'Usuario eliminado','{\"id\": 19, \"name\": \"aritzJefe\", \"email\": \"aritzjefe@sartu.com\", \"activo\": 1, \"estado\": \"activo\", \"rol_id\": 3, \"empresa_id\": null, \"fecha_registro\": \"2025-11-27T13:04:42.000000Z\", \"activation_token\": null, \"fecha_actualizacion\": \"2025-11-27T13:05:09.000000Z\"}',NULL,'2025-11-28 08:38:00','2025-11-28 08:38:00','2025-11-28 08:38:00',1,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(24,'login_fallido','Usuario',NULL,'Intento de acceso fallido',NULL,'{\"email\": \"aritzjefe@sartu.com\"}','2025-11-28 08:44:37','2025-11-28 08:44:37','2025-11-28 08:44:37',NULL,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(25,'login_fallido','Usuario',NULL,'Intento de acceso fallido',NULL,'{\"email\": \"aritzjefe@sartu.com\"}','2025-11-28 08:44:54','2025-11-28 08:44:54','2025-11-28 08:44:54',NULL,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(26,'login_correcto','Usuario',20,'Inicio de sesión correcto',NULL,NULL,'2025-11-28 08:45:52','2025-11-28 08:45:52','2025-11-28 08:45:52',20,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(27,'crear_usuario','Usuario',23,'Usuario creado con contraseña temporal',NULL,'{\"name\": \"login\", \"email\": \"login@gmail.com\", \"activo\": 0, \"estado\": \"pendiente\", \"rol_id\": \"3\", \"empresa_id\": \"1\"}','2025-11-28 09:02:03','2025-11-28 09:02:03','2025-11-28 09:02:03',1,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(28,'logout','Usuario',20,'Cierre de sesión',NULL,NULL,'2025-11-28 09:10:43','2025-11-28 09:10:43','2025-11-28 09:10:43',20,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(29,'login_correcto','Usuario',23,'Inicio de sesión correcto',NULL,NULL,'2025-11-28 09:12:17','2025-11-28 09:12:17','2025-11-28 09:12:17',23,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(30,'login_pendiente','Usuario',23,'Acceso inicial con contraseña temporal',NULL,NULL,'2025-11-28 09:12:17','2025-11-28 09:12:17','2025-11-28 09:12:17',23,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(31,'fichaje_entrada','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": null, \"lng\": null, \"tipo\": \"entrada\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 23}','2025-11-28 09:16:30','2025-11-28 09:16:30','2025-11-28 09:16:30',23,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(32,'fichaje_pausa','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": null, \"lng\": null, \"tipo\": \"pausa\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 23}','2025-11-28 09:19:54','2025-11-28 09:19:54','2025-11-28 09:19:54',23,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(33,'fichaje_reanudar','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": null, \"lng\": null, \"tipo\": \"reanudar\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 23}','2025-11-28 09:21:55','2025-11-28 09:21:55','2025-11-28 09:21:55',23,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(34,'fichaje_salida','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": null, \"lng\": null, \"tipo\": \"salida\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 23}','2025-11-28 09:23:18','2025-11-28 09:23:18','2025-11-28 09:23:18',23,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(35,'logout','Usuario',23,'Cierre de sesión',NULL,NULL,'2025-11-28 09:26:28','2025-11-28 09:26:28','2025-11-28 09:26:28',23,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(36,'logout','Usuario',1,'Cierre de sesión',NULL,NULL,'2025-11-28 09:51:00','2025-11-28 09:51:00','2025-11-28 09:51:00',1,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(37,'login_fallido','Usuario',NULL,'Intento de acceso fallido',NULL,'{\"email\": \"sartu@sartu.com\"}','2025-11-28 10:12:44','2025-11-28 10:12:44','2025-11-28 10:12:44',NULL,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(38,'login_correcto','Usuario',20,'Inicio de sesión correcto',NULL,NULL,'2025-11-28 10:20:13','2025-11-28 10:20:13','2025-11-28 10:20:13',20,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(39,'logout','Usuario',20,'Cierre de sesión',NULL,NULL,'2025-11-28 10:37:03','2025-11-28 10:37:03','2025-11-28 10:37:03',20,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(40,'login_correcto','Usuario',21,'Inicio de sesión correcto',NULL,NULL,'2025-11-28 10:37:22','2025-11-28 10:37:22','2025-11-28 10:37:22',21,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(41,'fichaje_entrada','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": null, \"lng\": null, \"tipo\": \"entrada\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 21}','2025-11-28 10:37:25','2025-11-28 10:37:25','2025-11-28 10:37:25',21,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(42,'fichaje_pausa','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": null, \"lng\": null, \"tipo\": \"pausa\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 21}','2025-11-28 10:40:51','2025-11-28 10:40:51','2025-11-28 10:40:51',21,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(43,'fichaje_reanudar','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": \"43.3084966\", \"lng\": \"-2.6685834\", \"tipo\": \"reanudar\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 21}','2025-11-28 10:55:24','2025-11-28 10:55:24','2025-11-28 10:55:24',21,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(44,'logout','Usuario',21,'Cierre de sesión',NULL,NULL,'2025-11-28 10:57:23','2025-11-28 10:57:23','2025-11-28 10:57:23',21,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(45,'login_correcto','Usuario',21,'Inicio de sesión correcto',NULL,NULL,'2025-11-28 10:57:37','2025-11-28 10:57:37','2025-11-28 10:57:37',21,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(46,'fichaje_pausa','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": null, \"lng\": null, \"tipo\": \"pausa\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 21}','2025-11-28 10:59:30','2025-11-28 10:59:30','2025-11-28 10:59:30',21,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(47,'fichaje_reanudar','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": null, \"lng\": null, \"tipo\": \"reanudar\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 21}','2025-11-28 10:59:37','2025-11-28 10:59:37','2025-11-28 10:59:37',21,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(48,'fichaje_salida','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": null, \"lng\": null, \"tipo\": \"salida\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 21}','2025-11-28 10:59:38','2025-11-28 10:59:38','2025-11-28 10:59:38',21,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(49,'logout','Usuario',21,'Cierre de sesión',NULL,NULL,'2025-11-28 10:59:42','2025-11-28 10:59:42','2025-11-28 10:59:42',21,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(50,'login_fallido','Usuario',NULL,'Intento de acceso fallido',NULL,'{\"email\": \"aritz.empleado@sartu.com\"}','2025-11-28 11:00:22','2025-11-28 11:00:22','2025-11-28 11:00:22',NULL,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(51,'login_correcto','Usuario',22,'Inicio de sesión correcto',NULL,NULL,'2025-11-28 11:00:45','2025-11-28 11:00:45','2025-11-28 11:00:45',22,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(52,'fichaje_entrada','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": null, \"lng\": null, \"tipo\": \"entrada\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 22}','2025-11-28 11:00:57','2025-11-28 11:00:57','2025-11-28 11:00:57',22,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(53,'fichaje_pausa','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": null, \"lng\": null, \"tipo\": \"pausa\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 22}','2025-11-28 11:00:59','2025-11-28 11:00:59','2025-11-28 11:00:59',22,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(54,'fichaje_reanudar','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": null, \"lng\": null, \"tipo\": \"reanudar\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 22}','2025-11-28 11:01:37','2025-11-28 11:01:37','2025-11-28 11:01:37',22,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(55,'fichaje_pausa','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": \"43.3084966\", \"lng\": \"-2.6685834\", \"tipo\": \"pausa\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 22}','2025-11-28 11:19:35','2025-11-28 11:19:35','2025-11-28 11:19:35',22,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(56,'fichaje_reanudar','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": null, \"lng\": null, \"tipo\": \"reanudar\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 22}','2025-11-28 11:30:37','2025-11-28 11:30:37','2025-11-28 11:30:37',22,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(57,'fichaje_salida','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": null, \"lng\": null, \"tipo\": \"salida\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 22}','2025-11-28 11:33:42','2025-11-28 11:33:42','2025-11-28 11:33:42',22,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(58,'logout','Usuario',22,'Cierre de sesión',NULL,NULL,'2025-11-28 11:33:45','2025-11-28 11:33:45','2025-11-28 11:33:45',22,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(59,'login_correcto','Usuario',1,'Inicio de sesión correcto',NULL,NULL,'2025-11-28 11:33:54','2025-11-28 11:33:54','2025-11-28 11:33:54',1,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(60,'logout','Usuario',1,'Cierre de sesión',NULL,NULL,'2025-11-28 11:41:14','2025-11-28 11:41:14','2025-11-28 11:41:14',1,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(61,'login_fallido','Usuario',NULL,'Intento de acceso fallido',NULL,'{\"email\": \"sartu@sartu.com\"}','2025-11-28 11:50:14','2025-11-28 11:50:14','2025-11-28 11:50:14',NULL,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(62,'login_correcto','Usuario',1,'Inicio de sesión correcto',NULL,NULL,'2025-11-28 11:50:23','2025-11-28 11:50:23','2025-11-28 11:50:23',1,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(63,'logout','Usuario',1,'Cierre de sesión',NULL,NULL,'2025-11-28 11:50:28','2025-11-28 11:50:28','2025-11-28 11:50:28',1,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(64,'login_fallido','Usuario',NULL,'Intento de acceso fallido',NULL,'{\"email\": \"sartu@sartu.com\"}','2025-11-28 11:56:57','2025-11-28 11:56:57','2025-11-28 11:56:57',NULL,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(65,'login_correcto','Usuario',1,'Inicio de sesión correcto',NULL,NULL,'2025-11-28 11:57:10','2025-11-28 11:57:10','2025-11-28 11:57:10',1,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(66,'login_correcto','Usuario',1,'Inicio de sesión correcto',NULL,NULL,'2025-11-28 12:43:22','2025-11-28 12:43:22','2025-11-28 12:43:22',1,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(67,'logout','Usuario',1,'Cierre de sesión',NULL,NULL,'2025-11-28 12:44:12','2025-11-28 12:44:12','2025-11-28 12:44:12',1,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(68,'login_fallido','Usuario',NULL,'Intento de acceso fallido',NULL,'{\"email\": \"aritzjefe@sartu.com\"}','2025-11-28 12:44:23','2025-11-28 12:44:23','2025-11-28 12:44:23',NULL,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(69,'login_correcto','Usuario',21,'Inicio de sesión correcto',NULL,NULL,'2025-11-28 12:44:35','2025-11-28 12:44:35','2025-11-28 12:44:35',21,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(70,'logout','Usuario',21,'Cierre de sesión',NULL,NULL,'2025-11-28 12:45:19','2025-11-28 12:45:19','2025-11-28 12:45:19',21,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(71,'login_correcto','Usuario',22,'Inicio de sesión correcto',NULL,NULL,'2025-11-28 12:45:36','2025-11-28 12:45:36','2025-11-28 12:45:36',22,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(72,'logout','Usuario',22,'Cierre de sesión',NULL,NULL,'2025-11-28 12:45:49','2025-11-28 12:45:49','2025-11-28 12:45:49',22,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(73,'login_correcto','Usuario',1,'Inicio de sesión correcto',NULL,NULL,'2025-12-01 09:04:35','2025-12-01 10:04:35','2025-12-01 10:04:35',1,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(74,'editar_empresa','Empresa',1,'Empresa actualizada','{\"nombre\": \"RK Solutions\", \"logo_path\": null, \"id_empresa\": 1, \"logo_thumb\": null, \"fecha_creacion\": \"2025-11-22T17:01:58.000000Z\", \"limite_usuarios\": 25, \"fecha_actualizacion\": \"2025-11-25T09:19:16.000000Z\", \"jornada_diaria_minutos\": 480, \"max_pausa_no_contabilizada\": 30}','{\"nombre\": \"RK Solutions\", \"logo_path\": \"logos/j1rQWA6woXk5xblYBP9gEK2T6ZtcYqQzseNSSmle.jpg\", \"id_empresa\": 1, \"logo_thumb\": \"logos/thumb_692d5b41e6323.jpg\", \"fecha_creacion\": \"2025-11-22T17:01:58.000000Z\", \"limite_usuarios\": \"25\", \"fecha_actualizacion\": \"2025-12-01T09:09:21.000000Z\", \"jornada_diaria_minutos\": \"480\", \"max_pausa_no_contabilizada\": \"30\"}','2025-12-01 09:09:21','2025-12-01 10:09:21','2025-12-01 10:09:21',1,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(75,'editar_empresa','Empresa',2,'Empresa actualizada','{\"nombre\": \"IPARTEK\", \"logo_path\": null, \"id_empresa\": 2, \"logo_thumb\": null, \"fecha_creacion\": \"2025-11-22T17:01:58.000000Z\", \"limite_usuarios\": 15, \"fecha_actualizacion\": \"2025-11-25T09:19:26.000000Z\", \"jornada_diaria_minutos\": 480, \"max_pausa_no_contabilizada\": 45}','{\"nombre\": \"IPARTEK\", \"logo_path\": \"logos/P20j4QXsWNJxmuzye2ZPvwVBNgn8zuYZ2rnL7nDH.png\", \"id_empresa\": 2, \"logo_thumb\": \"logos/thumb_692d5be67ebc2.jpg\", \"fecha_creacion\": \"2025-11-22T17:01:58.000000Z\", \"limite_usuarios\": \"15\", \"fecha_actualizacion\": \"2025-12-01T09:12:06.000000Z\", \"jornada_diaria_minutos\": \"480\", \"max_pausa_no_contabilizada\": \"45\"}','2025-12-01 09:12:06','2025-12-01 10:12:06','2025-12-01 10:12:06',1,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(76,'perfil_actualizado','Usuario',1,'Actualización de perfil','{\"id\": 1, \"name\": \"SuperAdmin\", \"email\": \"sartu@sartu.com\", \"activo\": 1, \"avatar\": null, \"estado\": \"activo\", \"rol_id\": 1, \"empresa_id\": 1, \"fecha_registro\": \"2025-11-22T23:33:36.000000Z\", \"activation_token\": null, \"fecha_actualizacion\": \"2025-11-23T08:44:12.000000Z\"}','{\"id\": 1, \"name\": \"SuperAdmin\", \"email\": \"sartu@sartu.com\", \"activo\": 1, \"avatar\": \"avatars/avatar_692d5e41da53b.jpg\", \"estado\": \"activo\", \"rol_id\": 1, \"empresa_id\": 1, \"fecha_registro\": \"2025-11-22T23:33:36.000000Z\", \"activation_token\": null, \"fecha_actualizacion\": \"2025-12-01T09:22:09.000000Z\"}','2025-12-01 09:22:09','2025-12-01 10:22:09','2025-12-01 10:22:09',1,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(77,'logout','Usuario',1,'Cierre de sesión',NULL,NULL,'2025-12-01 09:22:20','2025-12-01 10:22:20','2025-12-01 10:22:20',1,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(78,'login_correcto','Usuario',11,'Inicio de sesión correcto',NULL,NULL,'2025-12-01 09:22:34','2025-12-01 10:22:34','2025-12-01 10:22:34',11,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(79,'fichaje_entrada','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": \"43.309892\", \"lng\": \"-2.6685833\", \"tipo\": \"entrada\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 11}','2025-12-01 09:22:36','2025-12-01 10:22:36','2025-12-01 10:22:36',11,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(80,'fichaje_pausa','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": \"43.309892\", \"lng\": \"-2.6685833\", \"tipo\": \"pausa\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 11}','2025-12-01 09:23:40','2025-12-01 10:23:40','2025-12-01 10:23:40',11,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(81,'fichaje_reanudar','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": \"43.309892\", \"lng\": \"-2.6685833\", \"tipo\": \"reanudar\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 11}','2025-12-01 09:26:18','2025-12-01 10:26:18','2025-12-01 10:26:18',11,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(82,'logout','Usuario',11,'Cierre de sesión',NULL,NULL,'2025-12-01 09:31:18','2025-12-01 10:31:18','2025-12-01 10:31:18',11,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(83,'login_correcto','Usuario',12,'Inicio de sesión correcto',NULL,NULL,'2025-12-01 09:31:37','2025-12-01 10:31:37','2025-12-01 10:31:37',12,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(84,'fichaje_entrada','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": \"43.309892\", \"lng\": \"-2.6685833\", \"tipo\": \"entrada\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 12}','2025-12-01 09:31:39','2025-12-01 10:31:39','2025-12-01 10:31:39',12,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(85,'fichaje_pausa','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": \"43.309892\", \"lng\": \"-2.6685833\", \"tipo\": \"pausa\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 12}','2025-12-01 09:32:31','2025-12-01 10:32:31','2025-12-01 10:32:31',12,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(86,'fichaje_reanudar','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": \"43.309892\", \"lng\": \"-2.6685833\", \"tipo\": \"reanudar\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 12}','2025-12-01 09:32:44','2025-12-01 10:32:44','2025-12-01 10:32:44',12,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(87,'fichaje_salida','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": \"43.309892\", \"lng\": \"-2.6685833\", \"tipo\": \"salida\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 12}','2025-12-01 09:32:45','2025-12-01 10:32:45','2025-12-01 10:32:45',12,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(88,'logout','Usuario',12,'Cierre de sesión',NULL,NULL,'2025-12-01 09:32:53','2025-12-01 10:32:53','2025-12-01 10:32:53',12,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(89,'login_correcto','Usuario',11,'Inicio de sesión correcto',NULL,NULL,'2025-12-01 09:33:28','2025-12-01 10:33:28','2025-12-01 10:33:28',11,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(90,'fichaje_pausa','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": \"43.309892\", \"lng\": \"-2.6685833\", \"tipo\": \"pausa\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 11}','2025-12-01 09:33:32','2025-12-01 10:33:32','2025-12-01 10:33:32',11,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(91,'fichaje_reanudar','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": \"43.309892\", \"lng\": \"-2.6685833\", \"tipo\": \"reanudar\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 11}','2025-12-01 09:34:16','2025-12-01 10:34:16','2025-12-01 10:34:16',11,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(92,'fichaje_salida','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": \"43.309892\", \"lng\": \"-2.6685833\", \"tipo\": \"salida\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 11}','2025-12-01 09:38:27','2025-12-01 10:38:27','2025-12-01 10:38:27',11,'172.19.0.1','Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36'),(93,'login_correcto','Usuario',12,'Inicio de sesión correcto',NULL,NULL,'2025-12-01 09:39:57','2025-12-01 10:39:57','2025-12-01 10:39:57',12,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(94,'logout','Usuario',11,'Cierre de sesión',NULL,NULL,'2025-12-01 10:38:52','2025-12-01 11:38:52','2025-12-01 11:38:52',11,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(95,'login_fallido','Usuario',NULL,'Intento de acceso fallido',NULL,'{\"email\": \"sartu@sartu.com\"}','2025-12-01 12:06:01','2025-12-01 13:06:01','2025-12-01 13:06:01',NULL,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(96,'login_correcto','Usuario',1,'Inicio de sesión correcto',NULL,NULL,'2025-12-01 12:06:09','2025-12-01 13:06:09','2025-12-01 13:06:09',1,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(97,'editar_empresa','Empresa',2,'Empresa actualizada','{\"nombre\": \"IPARTEK\", \"logo_path\": \"logos/P20j4QXsWNJxmuzye2ZPvwVBNgn8zuYZ2rnL7nDH.png\", \"id_empresa\": 2, \"logo_thumb\": \"logos/thumb_692d5be67ebc2.jpg\", \"fecha_creacion\": \"2025-11-22T17:01:58.000000Z\", \"limite_usuarios\": 15, \"fecha_actualizacion\": \"2025-12-01T09:12:06.000000Z\", \"jornada_diaria_minutos\": 480, \"max_pausa_no_contabilizada\": 45}','{\"nombre\": \"IPARTEK\", \"logo_path\": \"logos/P20j4QXsWNJxmuzye2ZPvwVBNgn8zuYZ2rnL7nDH.png\", \"id_empresa\": 2, \"logo_thumb\": \"logos/thumb_692d5be67ebc2.jpg\", \"fecha_creacion\": \"2025-11-22T17:01:58.000000Z\", \"limite_usuarios\": \"18\", \"fecha_actualizacion\": \"2025-12-01T12:06:29.000000Z\", \"jornada_diaria_minutos\": \"480\", \"max_pausa_no_contabilizada\": \"45\"}','2025-12-01 12:06:29','2025-12-01 13:06:29','2025-12-01 13:06:29',1,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(98,'crear_usuario','Usuario',24,'Usuario creado con contraseña temporal',NULL,'{\"name\": \"kevin\", \"email\": \"kevin@sartu.com\", \"activo\": 0, \"estado\": \"pendiente\", \"rol_id\": \"3\", \"empresa_id\": \"1\"}','2025-12-01 12:07:05','2025-12-01 13:07:05','2025-12-01 13:07:05',1,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(99,'logout','Usuario',12,'Cierre de sesión',NULL,NULL,'2025-12-01 12:07:34','2025-12-01 13:07:34','2025-12-01 13:07:34',12,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(100,'login_correcto','Usuario',24,'Inicio de sesión correcto',NULL,NULL,'2025-12-01 12:08:18','2025-12-01 13:08:18','2025-12-01 13:08:18',24,'172.19.0.1','Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36'),(101,'login_pendiente','Usuario',24,'Acceso inicial con contraseña temporal',NULL,NULL,'2025-12-01 12:08:18','2025-12-01 13:08:18','2025-12-01 13:08:18',24,'172.19.0.1','Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36'),(102,'fichaje_entrada','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": \"43.309892\", \"lng\": \"-2.6685833\", \"tipo\": \"entrada\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 24}','2025-12-01 12:09:32','2025-12-01 13:09:32','2025-12-01 13:09:32',24,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(103,'fichaje_pausa','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": \"43.309892\", \"lng\": \"-2.6685833\", \"tipo\": \"pausa\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 24}','2025-12-01 12:09:55','2025-12-01 13:09:55','2025-12-01 13:09:55',24,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(104,'fichaje_reanudar','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": \"43.309892\", \"lng\": \"-2.6685833\", \"tipo\": \"reanudar\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 24}','2025-12-01 12:10:10','2025-12-01 13:10:10','2025-12-01 13:10:10',24,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(105,'fichaje_salida','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": \"43.309892\", \"lng\": \"-2.6685833\", \"tipo\": \"salida\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 24}','2025-12-01 12:10:15','2025-12-01 13:10:15','2025-12-01 13:10:15',24,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(106,'logout','Usuario',24,'Cierre de sesión',NULL,NULL,'2025-12-01 12:10:31','2025-12-01 13:10:31','2025-12-01 13:10:31',24,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(107,'login_correcto','Usuario',11,'Inicio de sesión correcto',NULL,NULL,'2025-12-01 12:10:43','2025-12-01 13:10:43','2025-12-01 13:10:43',11,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(108,'fichaje_entrada','Fichaje',NULL,'Fichaje realizado por el usuario',NULL,'{\"lat\": \"43.309892\", \"lng\": \"-2.6685833\", \"tipo\": \"entrada\", \"notas\": null, \"metodo\": \"web\", \"user_id\": 11}','2025-12-01 12:11:04','2025-12-01 13:11:04','2025-12-01 13:11:04',11,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(109,'logout','Usuario',1,'Cierre de sesión',NULL,NULL,'2025-12-01 12:16:53','2025-12-01 13:16:53','2025-12-01 13:16:53',1,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(110,'login_fallido','Usuario',NULL,'Intento de acceso fallido',NULL,'{\"email\": \"sartu@sartu.com\"}','2025-12-01 12:29:10','2025-12-01 13:29:10','2025-12-01 13:29:10',NULL,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(111,'login_fallido','Usuario',NULL,'Intento de acceso fallido',NULL,'{\"email\": \"sartu@sartu.com\"}','2025-12-01 12:29:21','2025-12-01 13:29:21','2025-12-01 13:29:21',NULL,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(112,'login_correcto','Usuario',1,'Inicio de sesión correcto',NULL,NULL,'2025-12-01 12:29:37','2025-12-01 13:29:37','2025-12-01 13:29:37',1,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),(113,'logout','Usuario',1,'Cierre de sesión',NULL,NULL,'2025-12-01 12:37:05','2025-12-01 13:37:05','2025-12-01 13:37:05',1,'172.19.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36');
/*!40000 ALTER TABLE `auditoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('sartu-cache-fichaje_metodo_activo_11','s:3:\"web\";',1764677464);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empresas`
--

DROP TABLE IF EXISTS `empresas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empresas` (
  `id_empresa` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `limite_usuarios` int NOT NULL DEFAULT '10',
  `jornada_diaria_minutos` int NOT NULL DEFAULT '480',
  `max_pausa_no_contabilizada` int DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `logo_thumb` varchar(255) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_empresa`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empresas`
--

LOCK TABLES `empresas` WRITE;
/*!40000 ALTER TABLE `empresas` DISABLE KEYS */;
INSERT INTO `empresas` VALUES (1,'RK Solutions',25,480,30,'logos/j1rQWA6woXk5xblYBP9gEK2T6ZtcYqQzseNSSmle.jpg','logos/thumb_692d5b41e6323.jpg','2025-11-22 18:01:58','2025-12-01 10:09:21'),(2,'IPARTEK',18,480,45,'logos/P20j4QXsWNJxmuzye2ZPvwVBNgn8zuYZ2rnL7nDH.png','logos/thumb_692d5be67ebc2.jpg','2025-11-22 18:01:58','2025-12-01 13:06:29');
/*!40000 ALTER TABLE `empresas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fichajes`
--

DROP TABLE IF EXISTS `fichajes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fichajes` (
  `id_fichaje` bigint NOT NULL AUTO_INCREMENT,
  `tipo` enum('entrada','salida','pausa','reanudar') NOT NULL,
  `fecha_hora` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lat` decimal(10,7) DEFAULT NULL,
  `lng` decimal(10,7) DEFAULT NULL,
  `notas` text,
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` int NOT NULL,
  `metodo_id` int NOT NULL,
  PRIMARY KEY (`id_fichaje`),
  UNIQUE KEY `unico_usuario_tipo_fecha` (`user_id`,`tipo`,`fecha_hora`),
  KEY `fichajes_ibfk_1` (`user_id`),
  KEY `fichajes_ibfk_2` (`metodo_id`),
  CONSTRAINT `fichajes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fichajes_ibfk_2` FOREIGN KEY (`metodo_id`) REFERENCES `metodos_fichaje` (`id_metodo`)
) ENGINE=InnoDB AUTO_INCREMENT=144 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fichajes`
--

LOCK TABLES `fichajes` WRITE;
/*!40000 ALTER TABLE `fichajes` DISABLE KEYS */;
INSERT INTO `fichajes` VALUES (1,'entrada','2025-11-25 08:48:48',43.3080819,-2.6685834,NULL,'2025-11-25 08:48:48','2025-11-25 08:48:48',11,1),(2,'salida','2025-11-25 08:55:27',43.3080819,-2.6685834,NULL,'2025-11-25 08:55:27','2025-11-25 08:55:27',11,1),(3,'entrada','2025-11-25 08:55:52',43.3080819,-2.6685834,NULL,'2025-11-25 08:55:52','2025-11-25 08:55:52',11,1),(4,'salida','2025-11-25 08:55:56',43.3080819,-2.6685834,NULL,'2025-11-25 08:55:56','2025-11-25 08:55:56',11,1),(5,'entrada','2025-11-25 08:56:00',43.3080819,-2.6685834,NULL,'2025-11-25 08:56:00','2025-11-25 08:56:00',11,1),(6,'salida','2025-11-25 08:56:08',43.3080819,-2.6685834,NULL,'2025-11-25 08:56:08','2025-11-25 08:56:08',11,1),(7,'entrada','2025-11-25 08:56:11',43.3080819,-2.6685834,NULL,'2025-11-25 08:56:11','2025-11-25 08:56:11',11,1),(8,'salida','2025-11-25 08:56:13',43.3080819,-2.6685834,NULL,'2025-11-25 08:56:13','2025-11-25 08:56:13',11,1),(9,'entrada','2025-11-25 09:00:45',43.3080819,-2.6685834,NULL,'2025-11-25 09:00:45','2025-11-25 09:00:45',11,1),(10,'salida','2025-11-25 09:03:45',43.3080819,-2.6685834,NULL,'2025-11-25 09:03:45','2025-11-25 09:03:45',11,1),(11,'entrada','2025-11-25 09:21:04',43.3080819,-2.6685834,NULL,'2025-11-25 09:21:04','2025-11-25 09:21:04',11,1),(12,'salida','2025-11-25 09:39:21',43.3080819,-2.6685834,NULL,'2025-11-25 09:39:21','2025-11-25 09:39:21',11,1),(13,'entrada','2025-11-25 09:39:42',43.3080819,-2.6685834,NULL,'2025-11-25 09:39:42','2025-11-25 09:39:42',11,1),(14,'salida','2025-11-25 09:40:09',43.3080819,-2.6685834,NULL,'2025-11-25 09:40:09','2025-11-25 09:40:09',11,1),(15,'entrada','2025-11-25 09:40:16',43.3080819,-2.6685834,NULL,'2025-11-25 09:40:16','2025-11-25 09:40:16',11,1),(16,'salida','2025-11-25 09:40:27',43.3080819,-2.6685834,NULL,'2025-11-25 09:40:27','2025-11-25 09:40:27',11,1),(17,'entrada','2025-11-25 09:53:23',43.3080819,-2.6685834,NULL,'2025-11-25 09:53:23','2025-11-25 09:53:23',11,1),(18,'pausa','2025-11-25 09:53:25',43.3080819,-2.6685834,NULL,'2025-11-25 09:53:25','2025-11-25 09:53:25',11,1),(19,'reanudar','2025-11-25 09:55:05',43.3080819,-2.6685834,NULL,'2025-11-25 09:55:05','2025-11-25 09:55:05',11,1),(20,'pausa','2025-11-25 10:17:15',43.3080819,-2.6685834,NULL,'2025-11-25 10:17:15','2025-11-25 10:17:15',11,1),(21,'reanudar','2025-11-25 10:17:25',43.3080819,-2.6685834,NULL,'2025-11-25 10:17:25','2025-11-25 10:17:25',11,1),(22,'pausa','2025-11-25 10:20:36',43.3080819,-2.6685834,NULL,'2025-11-25 10:20:36','2025-11-25 10:20:36',11,1),(23,'reanudar','2025-11-25 10:25:01',43.3080819,-2.6685834,NULL,'2025-11-25 10:25:01','2025-11-25 10:25:01',11,1),(24,'pausa','2025-11-25 10:25:27',43.3080819,-2.6685834,NULL,'2025-11-25 10:25:27','2025-11-25 10:25:27',11,1),(25,'reanudar','2025-11-25 10:25:31',43.3080819,-2.6685834,NULL,'2025-11-25 10:25:31','2025-11-25 10:25:31',11,1),(26,'pausa','2025-11-25 10:25:33',43.3080819,-2.6685834,NULL,'2025-11-25 10:25:33','2025-11-25 10:25:33',11,1),(27,'reanudar','2025-11-25 10:26:19',43.3080819,-2.6685834,NULL,'2025-11-25 10:26:19','2025-11-25 10:26:19',11,1),(28,'pausa','2025-11-25 10:35:09',43.3080819,-2.6685834,NULL,'2025-11-25 10:35:09','2025-11-25 10:35:09',11,1),(29,'reanudar','2025-11-25 10:44:10',43.3080819,-2.6685834,NULL,'2025-11-25 10:44:10','2025-11-25 10:44:10',11,1),(30,'pausa','2025-11-25 10:59:51',43.3080819,-2.6685834,NULL,'2025-11-25 10:59:51','2025-11-25 10:59:51',11,1),(31,'reanudar','2025-11-25 11:00:17',43.3080819,-2.6685834,NULL,'2025-11-25 11:00:17','2025-11-25 11:00:17',11,1),(32,'pausa','2025-11-25 11:00:36',43.3080819,-2.6685834,NULL,'2025-11-25 11:00:36','2025-11-25 11:00:36',11,1),(33,'reanudar','2025-11-25 11:00:44',43.3080819,-2.6685834,NULL,'2025-11-25 11:00:44','2025-11-25 11:00:44',11,1),(34,'salida','2025-11-25 11:01:17',43.3080819,-2.6685834,NULL,'2025-11-25 11:01:17','2025-11-25 11:01:17',11,1),(35,'entrada','2025-11-25 11:04:48',43.3080819,-2.6685834,NULL,'2025-11-25 11:04:48','2025-11-25 11:04:48',12,1),(36,'pausa','2025-11-25 11:10:16',43.3080819,-2.6685834,NULL,'2025-11-25 11:10:16','2025-11-25 11:10:16',12,1),(37,'reanudar','2025-11-25 11:18:35',43.3080819,-2.6685834,NULL,'2025-11-25 11:18:35','2025-11-25 11:18:35',12,1),(38,'entrada','2025-11-25 11:22:38',43.3080819,-2.6685834,NULL,'2025-11-25 11:22:38','2025-11-25 11:22:38',12,1),(39,'entrada','2025-11-25 11:22:40',43.3080819,-2.6685834,NULL,'2025-11-25 11:22:40','2025-11-25 11:22:40',12,1),(40,'entrada','2025-11-25 11:22:41',43.3080819,-2.6685834,NULL,'2025-11-25 11:22:41','2025-11-25 11:22:41',12,1),(41,'pausa','2025-11-25 11:22:45',43.3080819,-2.6685834,NULL,'2025-11-25 11:22:45','2025-11-25 11:22:45',12,1),(42,'entrada','2025-11-25 11:22:56',43.3080819,-2.6685834,NULL,'2025-11-25 11:22:56','2025-11-25 11:22:56',12,1),(43,'entrada','2025-11-25 11:22:58',43.3080819,-2.6685834,NULL,'2025-11-25 11:22:58','2025-11-25 11:22:58',12,1),(44,'entrada','2025-11-25 11:22:59',43.3080819,-2.6685834,NULL,'2025-11-25 11:22:59','2025-11-25 11:22:59',12,1),(45,'entrada','2025-11-25 11:23:00',43.3080819,-2.6685834,NULL,'2025-11-25 11:23:00','2025-11-25 11:23:00',12,1),(46,'entrada','2025-11-25 11:23:01',43.3080819,-2.6685834,NULL,'2025-11-25 11:23:01','2025-11-25 11:23:01',12,1),(47,'entrada','2025-11-25 11:23:02',43.3080819,-2.6685834,NULL,'2025-11-25 11:23:02','2025-11-25 11:23:02',12,1),(48,'entrada','2025-11-25 11:23:03',43.3080819,-2.6685834,NULL,'2025-11-25 11:23:03','2025-11-25 11:23:03',12,1),(49,'salida','2025-11-25 11:23:23',43.3080819,-2.6685834,NULL,'2025-11-25 11:23:23','2025-11-25 11:23:23',12,1),(50,'entrada','2025-11-25 11:24:13',43.3080819,-2.6685834,NULL,'2025-11-25 11:24:13','2025-11-25 11:24:13',12,1),(51,'entrada','2025-11-25 11:24:14',43.3080819,-2.6685834,NULL,'2025-11-25 11:24:14','2025-11-25 11:24:14',12,1),(53,'entrada','2025-11-25 11:24:15',43.3080819,-2.6685834,NULL,'2025-11-25 11:24:15','2025-11-25 11:24:15',12,1),(54,'entrada','2025-11-25 11:24:16',43.3080819,-2.6685834,NULL,'2025-11-25 11:24:16','2025-11-25 11:24:16',12,1),(55,'entrada','2025-11-25 11:24:17',43.3080819,-2.6685834,NULL,'2025-11-25 11:24:17','2025-11-25 11:24:17',12,1),(56,'entrada','2025-11-25 11:24:27',43.3080819,-2.6685834,NULL,'2025-11-25 11:24:27','2025-11-25 11:24:27',12,1),(57,'salida','2025-11-25 11:27:22',43.3080819,-2.6685834,NULL,'2025-11-25 11:27:22','2025-11-25 11:27:22',12,1),(58,'entrada','2025-11-25 12:40:19',43.3080819,-2.6685834,NULL,'2025-11-25 12:40:19','2025-11-25 12:40:19',12,1),(59,'salida','2025-11-25 12:51:08',43.3080819,-2.6685834,NULL,'2025-11-25 12:51:08','2025-11-25 12:51:08',12,1),(60,'entrada','2025-11-26 08:03:57',43.3366994,-2.6741718,NULL,'2025-11-26 08:03:57','2025-11-26 08:03:57',11,1),(61,'pausa','2025-11-26 08:17:45',43.3366994,-2.6741718,NULL,'2025-11-26 08:17:45','2025-11-26 08:17:45',11,1),(62,'reanudar','2025-11-26 08:22:20',43.3366994,-2.6741718,NULL,'2025-11-26 08:22:20','2025-11-26 08:22:20',11,1),(63,'pausa','2025-11-26 08:44:27',43.3366994,-2.6741718,NULL,'2025-11-26 08:44:27','2025-11-26 08:44:27',11,1),(64,'reanudar','2025-11-26 08:48:29',43.3366994,-2.6741718,NULL,'2025-11-26 08:48:29','2025-11-26 08:48:29',11,1),(65,'pausa','2025-11-26 09:06:25',43.3366994,-2.6741718,NULL,'2025-11-26 09:06:25','2025-11-26 09:06:25',11,1),(66,'reanudar','2025-11-26 09:14:20',43.3366994,-2.6741718,NULL,'2025-11-26 09:14:20','2025-11-26 09:14:20',11,1),(67,'pausa','2025-11-26 09:26:21',43.3366994,-2.6741718,NULL,'2025-11-26 09:26:21','2025-11-26 09:26:21',11,1),(68,'reanudar','2025-11-26 09:31:23',43.3366994,-2.6741718,NULL,'2025-11-26 09:31:23','2025-11-26 09:31:23',11,1),(69,'entrada','2025-11-26 09:35:54',43.3366994,-2.6741718,NULL,'2025-11-26 09:35:54','2025-11-26 09:35:54',12,1),(70,'salida','2025-11-26 09:37:15',43.3366994,-2.6741718,NULL,'2025-11-26 09:37:15','2025-11-26 09:37:15',12,1),(71,'entrada','2025-11-26 09:37:30',43.3366994,-2.6741718,NULL,'2025-11-26 09:37:30','2025-11-26 09:37:30',12,1),(72,'salida','2025-11-26 09:38:27',43.3366994,-2.6741718,NULL,'2025-11-26 09:38:27','2025-11-26 09:38:27',12,1),(73,'entrada','2025-11-26 09:43:54',43.3094656,-2.6705920,NULL,'2025-11-26 09:43:54','2025-11-26 09:43:54',12,1),(74,'pausa','2025-11-26 09:50:38',43.3094656,-2.6705920,NULL,'2025-11-26 09:50:38','2025-11-26 09:50:38',12,1),(75,'reanudar','2025-11-26 09:55:28',43.3094656,-2.6705920,NULL,'2025-11-26 09:55:28','2025-11-26 09:55:28',12,1),(76,'salida','2025-11-26 10:07:28',43.3094656,-2.6705920,NULL,'2025-11-26 10:07:28','2025-11-26 10:07:28',12,1),(77,'pausa','2025-11-26 10:35:54',43.3094656,-2.6705920,NULL,'2025-11-26 10:35:54','2025-11-26 10:35:54',11,1),(78,'reanudar','2025-11-26 11:07:27',43.3094656,-2.6705920,NULL,'2025-11-26 11:07:27','2025-11-26 11:07:27',11,1),(79,'salida','2025-11-26 11:11:05',43.3094656,-2.6705920,NULL,'2025-11-26 11:11:05','2025-11-26 11:11:05',11,1),(80,'entrada','2025-11-26 11:16:43',NULL,NULL,NULL,'2025-11-26 11:16:43','2025-11-26 11:16:43',11,1),(81,'pausa','2025-11-26 11:17:54',43.3094656,-2.6705920,NULL,'2025-11-26 11:17:54','2025-11-26 11:17:54',11,1),(82,'reanudar','2025-11-26 11:24:49',43.3094656,-2.6705920,NULL,'2025-11-26 11:24:49','2025-11-26 11:24:49',11,1),(83,'salida','2025-11-26 11:29:07',43.3094656,-2.6705920,NULL,'2025-11-26 11:29:07','2025-11-26 11:29:07',11,1),(84,'entrada','2025-11-26 11:45:05',43.3094656,-2.6705920,NULL,'2025-11-26 11:45:05','2025-11-26 11:45:05',11,1),(85,'entrada','2025-11-26 11:50:09',43.3094656,-2.6705920,NULL,'2025-11-26 11:50:09','2025-11-26 11:50:09',13,1),(86,'entrada','2025-11-26 11:57:46',43.3094656,-2.6705920,NULL,'2025-11-26 11:57:46','2025-11-26 11:57:46',14,1),(87,'salida','2025-11-26 11:59:17',43.3094656,-2.6705920,NULL,'2025-11-26 11:59:17','2025-11-26 11:59:17',14,1),(88,'pausa','2025-11-26 12:04:05',43.3094656,-2.6705920,NULL,'2025-11-26 12:04:05','2025-11-26 12:04:05',13,1),(89,'reanudar','2025-11-26 12:26:32',43.3094656,-2.6705920,NULL,'2025-11-26 12:26:32','2025-11-26 12:26:32',13,1),(90,'salida','2025-11-26 12:27:56',43.3094656,-2.6705920,NULL,'2025-11-26 12:27:56','2025-11-26 12:27:56',13,1),(91,'entrada','2025-11-26 12:36:05',43.3094656,-2.6705920,NULL,'2025-11-26 12:36:05','2025-11-26 12:36:05',13,1),(92,'pausa','2025-11-26 12:52:01',43.3098184,-2.6741719,NULL,'2025-11-26 12:52:01','2025-11-26 12:52:01',13,1),(93,'reanudar','2025-11-26 12:52:38',43.3098184,-2.6741719,NULL,'2025-11-26 12:52:38','2025-11-26 12:52:38',13,1),(94,'entrada','2025-11-26 12:57:00',43.3098184,-2.6741719,NULL,'2025-11-26 12:57:00','2025-11-26 12:57:00',12,1),(95,'salida','2025-11-26 12:57:01',43.3098184,-2.6741719,NULL,'2025-11-26 12:57:01','2025-11-26 12:57:01',12,1),(96,'salida','2025-11-27 07:53:38',43.3098184,-2.6741719,NULL,'2025-11-27 07:53:38','2025-11-27 07:53:38',11,1),(97,'entrada','2025-11-27 08:06:08',43.3098184,-2.6741719,NULL,'2025-11-27 08:06:08','2025-11-27 08:06:08',11,1),(98,'pausa','2025-11-27 08:16:00',43.3098184,-2.6741719,NULL,'2025-11-27 08:16:00','2025-11-27 08:16:00',11,1),(99,'reanudar','2025-11-27 08:16:26',43.3098184,-2.6741719,NULL,'2025-11-27 08:16:26','2025-11-27 08:16:26',11,1),(100,'salida','2025-11-27 08:16:33',43.3098184,-2.6741719,NULL,'2025-11-27 08:16:33','2025-11-27 08:16:33',11,1),(101,'entrada','2025-11-27 09:30:22',43.3098184,-2.6741719,NULL,'2025-11-27 09:30:22','2025-11-27 09:30:22',11,1),(102,'pausa','2025-11-27 10:38:01',43.3098184,-2.6741719,NULL,'2025-11-27 10:38:01','2025-11-27 10:38:01',11,1),(103,'reanudar','2025-11-27 10:46:19',43.3098184,-2.6741719,NULL,'2025-11-27 10:46:19','2025-11-27 10:46:19',11,1),(104,'salida','2025-11-27 10:57:16',43.3098184,-2.6741719,NULL,'2025-11-27 10:57:16','2025-11-27 10:57:16',11,1),(105,'entrada','2025-11-28 08:16:39',NULL,NULL,NULL,'2025-11-28 08:16:39','2025-11-28 08:16:39',11,1),(106,'pausa','2025-11-28 08:24:18',NULL,NULL,NULL,'2025-11-28 08:24:18','2025-11-28 08:24:18',11,1),(107,'reanudar','2025-11-28 08:26:18',NULL,NULL,NULL,'2025-11-28 08:26:18','2025-11-28 08:26:18',11,1),(108,'salida','2025-11-28 08:26:32',NULL,NULL,NULL,'2025-11-28 08:26:32','2025-11-28 08:26:32',11,1),(109,'entrada','2025-11-28 08:27:09',NULL,NULL,NULL,'2025-11-28 08:27:09','2025-11-28 08:27:09',12,1),(110,'pausa','2025-11-28 08:28:10',NULL,NULL,NULL,'2025-11-28 08:28:10','2025-11-28 08:28:10',12,1),(111,'reanudar','2025-11-28 08:29:12',NULL,NULL,NULL,'2025-11-28 08:29:12','2025-11-28 08:29:12',12,1),(112,'salida','2025-11-28 08:29:18',NULL,NULL,NULL,'2025-11-28 08:29:18','2025-11-28 08:29:18',12,1),(113,'entrada','2025-11-28 09:16:30',NULL,NULL,NULL,'2025-11-28 09:16:30','2025-11-28 09:16:30',23,1),(114,'pausa','2025-11-28 09:19:54',NULL,NULL,NULL,'2025-11-28 09:19:54','2025-11-28 09:19:54',23,1),(115,'reanudar','2025-11-28 09:21:55',NULL,NULL,NULL,'2025-11-28 09:21:55','2025-11-28 09:21:55',23,1),(116,'salida','2025-11-28 09:23:18',NULL,NULL,NULL,'2025-11-28 09:23:18','2025-11-28 09:23:18',23,1),(117,'entrada','2025-11-28 10:37:25',NULL,NULL,NULL,'2025-11-28 10:37:25','2025-11-28 10:37:25',21,1),(118,'pausa','2025-11-28 10:40:51',NULL,NULL,NULL,'2025-11-28 10:40:51','2025-11-28 10:40:51',21,1),(119,'reanudar','2025-11-28 10:55:24',43.3084966,-2.6685834,NULL,'2025-11-28 10:55:24','2025-11-28 10:55:24',21,1),(120,'pausa','2025-11-28 10:59:30',NULL,NULL,NULL,'2025-11-28 10:59:30','2025-11-28 10:59:30',21,1),(121,'reanudar','2025-11-28 10:59:37',NULL,NULL,NULL,'2025-11-28 10:59:37','2025-11-28 10:59:37',21,1),(122,'salida','2025-11-28 10:59:38',NULL,NULL,NULL,'2025-11-28 10:59:38','2025-11-28 10:59:38',21,1),(123,'entrada','2025-11-28 11:00:56',NULL,NULL,NULL,'2025-11-28 11:00:56','2025-11-28 11:00:56',22,1),(124,'pausa','2025-11-28 11:00:59',NULL,NULL,NULL,'2025-11-28 11:00:59','2025-11-28 11:00:59',22,1),(125,'reanudar','2025-11-28 11:01:37',NULL,NULL,NULL,'2025-11-28 11:01:37','2025-11-28 11:01:37',22,1),(126,'pausa','2025-11-28 11:19:35',43.3084966,-2.6685834,NULL,'2025-11-28 11:19:35','2025-11-28 11:19:35',22,1),(127,'reanudar','2025-11-28 11:30:37',NULL,NULL,NULL,'2025-11-28 11:30:37','2025-11-28 11:30:37',22,1),(128,'salida','2025-11-28 11:33:42',NULL,NULL,NULL,'2025-11-28 11:33:42','2025-11-28 11:33:42',22,1),(129,'entrada','2025-12-01 10:22:36',43.3098920,-2.6685833,NULL,'2025-12-01 10:22:36','2025-12-01 10:22:36',11,1),(130,'pausa','2025-12-01 10:23:40',43.3098920,-2.6685833,NULL,'2025-12-01 10:23:40','2025-12-01 10:23:40',11,1),(131,'reanudar','2025-12-01 10:26:18',43.3098920,-2.6685833,NULL,'2025-12-01 10:26:18','2025-12-01 10:26:18',11,1),(132,'entrada','2025-12-01 10:31:39',43.3098920,-2.6685833,NULL,'2025-12-01 10:31:39','2025-12-01 10:31:39',12,1),(133,'pausa','2025-12-01 10:32:31',43.3098920,-2.6685833,NULL,'2025-12-01 10:32:31','2025-12-01 10:32:31',12,1),(134,'reanudar','2025-12-01 10:32:44',43.3098920,-2.6685833,NULL,'2025-12-01 10:32:44','2025-12-01 10:32:44',12,1),(135,'salida','2025-12-01 10:32:45',43.3098920,-2.6685833,NULL,'2025-12-01 10:32:45','2025-12-01 10:32:45',12,1),(136,'pausa','2025-12-01 10:33:32',43.3098920,-2.6685833,NULL,'2025-12-01 10:33:32','2025-12-01 10:33:32',11,1),(137,'reanudar','2025-12-01 10:34:16',43.3098920,-2.6685833,NULL,'2025-12-01 10:34:16','2025-12-01 10:34:16',11,1),(138,'salida','2025-12-01 10:38:27',43.3098920,-2.6685833,NULL,'2025-12-01 10:38:27','2025-12-01 10:38:27',11,1),(139,'entrada','2025-12-01 13:09:32',43.3098920,-2.6685833,NULL,'2025-12-01 13:09:32','2025-12-01 13:09:32',24,1),(140,'pausa','2025-12-01 13:09:55',43.3098920,-2.6685833,NULL,'2025-12-01 13:09:55','2025-12-01 13:09:55',24,1),(141,'reanudar','2025-12-01 13:10:10',43.3098920,-2.6685833,NULL,'2025-12-01 13:10:10','2025-12-01 13:10:10',24,1),(142,'salida','2025-12-01 13:10:15',43.3098920,-2.6685833,NULL,'2025-12-01 13:10:15','2025-12-01 13:10:15',24,1),(143,'entrada','2025-12-01 13:11:04',43.3098920,-2.6685833,NULL,'2025-12-01 13:11:04','2025-12-01 13:11:04',11,1);
/*!40000 ALTER TABLE `fichajes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `metodos_fichaje`
--

DROP TABLE IF EXISTS `metodos_fichaje`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `metodos_fichaje` (
  `id_metodo` int NOT NULL AUTO_INCREMENT,
  `slug` varchar(50) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_metodo`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `metodos_fichaje`
--

LOCK TABLES `metodos_fichaje` WRITE;
/*!40000 ALTER TABLE `metodos_fichaje` DISABLE KEYS */;
INSERT INTO `metodos_fichaje` VALUES (1,'web','web_app','Fichaje desde navegador web','2025-11-22 17:32:48','2025-12-01 08:34:52'),(2,'pwa','pwa_movil','Fichaje desde la PWA en movil','2025-11-22 17:32:48','2025-12-01 08:34:52'),(3,'nfc','nfc','Fichaje mediante tarjeta NFC','2025-11-22 17:32:48','2025-12-01 08:34:52'),(4,'pc','pc_autolog','Fichaje automtico al encender PC','2025-11-22 17:32:48','2025-12-01 08:34:52');
/*!40000 ALTER TABLE `metodos_fichaje` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2025_11_17_202607_create_sessions_table',1),(2,'2025_11_19_103851_create_cache_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resumen_diario`
--

DROP TABLE IF EXISTS `resumen_diario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `resumen_diario` (
  `id_resumen` bigint NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `tiempo_trabajado` int DEFAULT '0',
  `tiempo_pausas` int DEFAULT '0',
  `tiempo_total` int DEFAULT '0',
  `actualizado` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id_resumen`),
  UNIQUE KEY `id_usuario` (`user_id`,`fecha`),
  CONSTRAINT `resumen_diario_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resumen_diario`
--

LOCK TABLES `resumen_diario` WRITE;
/*!40000 ALTER TABLE `resumen_diario` DISABLE KEYS */;
INSERT INTO `resumen_diario` VALUES (1,'2025-11-25',80,17,97,'2025-11-25 11:01:17',11),(2,'2025-11-25',20,0,20,'2025-11-25 12:51:08',12),(3,'2025-11-26',140,32,171,'2025-11-26 11:29:07',11),(4,'2025-11-26',21,0,21,'2025-11-26 10:07:28',12),(5,'2025-11-26',31,0,31,'2025-11-26 12:52:01',13),(6,'2025-11-26',2,0,2,'2025-11-26 11:59:17',14),(7,'2025-11-27',89,9,97,'2025-11-27 10:57:16',11),(8,'2025-11-28',8,2,10,'2025-11-28 08:26:18',11),(9,'2025-11-28',1,1,2,'2025-11-28 08:29:12',12),(10,'2025-11-28',5,2,7,'2025-11-28 09:23:18',23),(11,'2025-11-28',8,15,22,'2025-11-28 10:59:30',21),(12,'2025-11-28',21,12,33,'2025-11-28 11:33:42',22),(13,'2025-12-01',12,3,16,'2025-12-01 09:38:27',11),(14,'2025-12-01',1,0,1,'2025-12-01 09:32:31',12),(15,'2025-12-01',0,0,1,'2025-12-01 12:10:10',24);
/*!40000 ALTER TABLE `resumen_diario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id_rol` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_rol`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin_sistema','2025-11-22 17:32:48','2025-11-22 17:53:31'),(2,'encargado','2025-11-22 17:32:48','2025-11-22 17:53:31'),(3,'empleado','2025-11-22 17:32:48','2025-11-22 17:32:48');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `activation_token` varchar(255) DEFAULT NULL,
  `estado` enum('pendiente','activo','bloqueado') NOT NULL DEFAULT 'pendiente',
  `activo` tinyint(1) DEFAULT '1',
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `empresa_id` int DEFAULT NULL,
  `rol_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `users_ibfk_1` (`empresa_id`),
  KEY `users_ibfk_2` (`rol_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id_empresa`) ON DELETE SET NULL,
  CONSTRAINT `users_ibfk_2` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'SuperAdmin','sartu@sartu.com','$2y$12$nMvCZ1LgXeDcU1NEGmgszuAT4syOnXPMML9JAp7BzWEofNDdShkQe','avatars/avatar_692d5e41da53b.jpg',NULL,NULL,'activo',1,'2025-11-23 00:33:36','2025-12-01 10:22:09',1,1),(11,'jefe1','jefe@sartu.com','$2y$12$PWl.Aa3bm1oTVSfEEKa9NudbC9Ah03nyI7vJF31GcfcH6owjdzoCq',NULL,NULL,NULL,'activo',1,'2025-11-24 10:42:07','2025-11-27 11:23:51',1,2),(12,'Empleado1','empleado1@sartu.com','$2y$12$vZ.Ks8gnbUR2TNvCDl0B5OqeGmVQChRcvSNV3GWf3.KmYAAEpjMT6',NULL,NULL,NULL,'activo',1,'2025-11-24 11:12:02','2025-11-24 11:31:19',1,3),(13,'jefe2','jefe2@sartu.com','$2y$12$94A4w.TTqA85Y8TZ3IZx7e/i5g0deg37MVnMYNPChm7fdgkeA6piO',NULL,NULL,NULL,'activo',2,'2025-11-26 11:49:52','2025-11-27 11:23:51',2,2),(14,'Empleado2','empresa2@sartu.com','$2y$12$9ErGizmeYACtB23./.3VGuZoY29WPxBYb1vmJeNixlR7frwoG92pi',NULL,NULL,NULL,'pendiente',2,'2025-11-26 11:57:36','2025-11-27 11:23:51',2,3),(20,'Ametz','ametz@sartu.com','$2y$12$bi0hqL1d.e8t8bYihKGaZup83EJIYlqb1MO3MU1A8LQBu/lcuYXgW',NULL,NULL,NULL,'activo',1,'2025-11-27 13:08:11','2025-11-27 13:12:31',2,1),(21,'Ametz Jefe','ametz.jefe@sartu.com','$2y$12$Xzozl.hOUfDKgkL1J7xNzO1bgtlprJkNAbPGy0pNEBY03PTkc6yCi',NULL,NULL,NULL,'activo',1,'2025-11-27 13:09:29','2025-11-28 08:37:09',2,2),(22,'Ametz Empleado','ametz.empleado@sartu.com','$2y$12$6LEU4/WHmspg15f3ly4ih.4JE80nlso7JMa8RKkVib08B1JA4E7ia',NULL,NULL,NULL,'activo',1,'2025-11-27 13:10:52','2025-11-28 08:37:44',2,3),(23,'login','login@gmail.com','$2y$12$zcnZ9tmbw5XX/yY6ZYfinuLA0QDvBj8XvRjZ4kKiSH4Z.o04lPbqu',NULL,NULL,NULL,'activo',1,'2025-11-28 09:02:03','2025-11-28 09:16:22',1,3),(24,'kevin','kevin@sartu.com','$2y$12$wi1wYEZWFWjsZEQ.Exeqv.CpFxW0CVwh4PPfzd.oDzoS4tGfagvZq',NULL,NULL,NULL,'activo',1,'2025-12-01 13:07:05','2025-12-01 13:08:47',1,3);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-01 13:43:25
