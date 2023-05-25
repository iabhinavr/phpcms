-- MariaDB dump 10.19  Distrib 10.10.2-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: phpcms
-- ------------------------------------------------------
-- Server version	10.10.2-MariaDB-1:10.10.2+maria~ubu2204

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `published` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `image` int(11) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `excerpt` text DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
INSERT INTO `articles` VALUES
(1,'5 Travel Apps You Should Check','{\"time\":1676899334993,\"blocks\":[{\"id\":\"YFe64GiY-2\",\"type\":\"paragraph\",\"data\":{\"text\":\"We are using EditorJS to write content.\"}},{\"id\":\"f8ANP_Tx1A\",\"type\":\"header\",\"data\":{\"text\":\"Sample Heading\",\"level\":2}},{\"id\":\"cj7t6wcXkI\",\"type\":\"paragraph\",\"data\":{\"text\":\"This is a paragraph. Here goes a line.\"}},{\"id\":\"3D1wT38SmG\",\"type\":\"paragraph\",\"data\":{\"text\":\"Here is a list:\"}},{\"id\":\"jcwadXrnwX\",\"type\":\"list\",\"data\":{\"style\":\"ordered\",\"items\":[\"Item 1\",\"Item 2&nbsp;\",\"Item 3\"]}},{\"id\":\"p78UGtXpnt\",\"type\":\"image\",\"data\":{\"file\":{\"url\":\"../uploads/fullsize/2023/02/ngorongoro.jpg\"},\"caption\":\"Zebras of Ngorongoro\",\"withBorder\":false,\"stretched\":false,\"withBackground\":false}},{\"id\":\"3-4QOU_fM0\",\"type\":\"paragraph\",\"data\":{\"text\":\"And this is a <b>hyperlink</b>: <a href=\\\"http://example.com\\\">Hello World!</a>\"}},{\"id\":\"wPQcBbgplv\",\"type\":\"image\",\"data\":{\"file\":{\"url\":\"../uploads/fullsize/2023/02/lake-wanaka.jpg\"},\"caption\":\"Lake Wanaka\",\"withBorder\":false,\"stretched\":false,\"withBackground\":false}},{\"id\":\"GyXE0d_gIN\",\"type\":\"image\",\"data\":{\"file\":{\"url\":\"../uploads/fullsize/2023/02/Jungfraujoch.jpg\"},\"caption\":\"\",\"withBorder\":false,\"stretched\":false,\"withBackground\":false}},{\"id\":\"chmzrfRvwA\",\"type\":\"image\",\"data\":{\"file\":{\"url\":\"../uploads/fullsize/2023/02/kilimanjaro.jpg\"},\"caption\":\"\",\"withBorder\":false,\"stretched\":false,\"withBackground\":false}}],\"version\":\"2.26.5\"}','2022-12-20 14:58:48','2023-02-20 13:22:14',59,'travel-apps-must','Suspendisse potenti nullam ac tortor vitae purus faucibus ornare. Lorem sed risus ultricies tristique nulla aliquet. Vivamus at augue eget arcu dictum varius duis at consectetur. Vitae aliquet nec ullamcorper sit amet.','abhinavr'),
(2,'12 Locations to Photograph Fall Colors','{\"time\":1676453018407,\"blocks\":[{\"id\":\"L7aSJASDtl\",\"type\":\"header\",\"data\":{\"text\":\"<a href=\\\"https://editorjs.io/getting-started/#tools-installation\\\">Tools installation</a>\",\"level\":2}},{\"id\":\"sNMOU3ituv\",\"type\":\"paragraph\",\"data\":{\"text\":\"As described in&nbsp;<a href=\\\"https://editorjs.io/base-concepts\\\">Base Concepts</a>, each Block in Editor.js is provided by a Plugin.&nbsp;There are simple external scripts with their own logic.&nbsp;\"}},{\"id\":\"uTdjV0XKKE\",\"type\":\"paragraph\",\"data\":{\"text\":\"There is the only Paragraph block already included in Editor.js. Probably you want to use several Block Tools that should be installed and connected.\"}},{\"id\":\"vc7Z_bQJ6d\",\"type\":\"image\",\"data\":{\"file\":{\"url\":\"../uploads/fullsize/2023/02/patagonia.jpg\"},\"caption\":\"\",\"withBorder\":false,\"stretched\":false,\"withBackground\":false}},{\"id\":\"NEVlvPPmBX\",\"type\":\"paragraph\",\"data\":{\"text\":\"You can find some available Blocks&nbsp;<a href=\\\"https://github.com/editor-js\\\">here</a>. Select the Blocks you need and follow the installation guide in their README.md files.\"}}],\"version\":\"2.26.5\"}','2022-12-31 15:08:05','2023-02-15 09:23:38',58,'photograph-fall-colors','Pellentesque dignissim enim sit amet venenatis urna cursus. Dui accumsan sit amet nulla facilisi. Ullamcorper malesuada proin libero nunc consequat interdum varius.','testuser'),
(3,'Best Skiing Location in Himachal','{\"time\":1676453208937,\"blocks\":[{\"id\":\"8SwQ6PW5ca\",\"type\":\"paragraph\",\"data\":{\"text\":\"Here are some of the best skiing locations in Himalays you should visit in your lifetime:\"}},{\"id\":\"_ZCM3MHSBr\",\"type\":\"list\",\"data\":{\"style\":\"ordered\",\"items\":[\"Auli\",\"Uttarakhand\",\"Manali\"]}}],\"version\":\"2.26.5\"}','2023-01-15 15:08:15','2023-02-15 09:26:48',59,'skiing-himachal','Egestas sed tempus urna et pharetra pharetra massa massa ultricies. Lorem ipsum dolor sit amet. Libero justo laoreet sit amet cursus sit. Et ligula ullamcorper malesuada proin libero nunc consequat.','testuser'),
(4,'How to Choose a Hiking Backpack','{\"time\":1678207034837,\"blocks\":[{\"id\":\"pWcsC9UZb9\",\"type\":\"paragraph\",\"data\":{\"text\":\"Quam nulla porttitor massa id. Vel facilisis volutpat est velit egestas dui. Tortor at risus viverra adipiscing at in. Mauris cursus mattis molestie a iaculis at. Sapien pellentesque habitant morbi tristique senectus et netus.\"}},{\"id\":\"P_oG812wyz\",\"type\":\"paragraph\",\"data\":{\"text\":\"Ut porttitor leo a diam sollicitudin. Arcu risus&nbsp;<a href=\\\"http://example.com/\\\">quis varius quam</a>&nbsp;quisque id diam. Non curabitur gravida arcu ac tortor dignissim. Quis commodo odio aenean sed. Tincidunt lobortis feugiat vivamus at augue eget arcu dictum varius. Sagittis orci a scelerisque purus. Curabitur vitae nunc sed velit dignissim sodales ut eu sem. Et malesuada fames ac turpis egestas integer.\"}},{\"id\":\"3Wp0pt2kJ8\",\"type\":\"list\",\"data\":{\"style\":\"unordered\",\"items\":[\"Item 1\",\"Item 2\",\"Item 3\"]}},{\"id\":\"MFjhrrHP3O\",\"type\":\"paragraph\",\"data\":{\"text\":\"Turpis nunc eget&nbsp;<a href=\\\"http://google.com/\\\">lorem</a>&nbsp;dolor sed viverra ipsum nunc aliquet. Tincidunt eget nullam non nisi. Arcu bibendum at varius vel pharetra. Magna ac placerat vestibulum lectus.\"}},{\"id\":\"sDX3BHRNdy\",\"type\":\"paragraph\",\"data\":{\"text\":\"In hendrerit gravida rutrum quisque non. Viverra tellus in hac habitasse. Est sit amet facilisis magna etiam tempor orci eu. Odio pellentesque diam volutpat commodo sed egestas. At varius vel pharetra vel turpis nunc eget lorem. At augue eget arcu dictum varius duis. Velit scelerisque in dictum non consectetur a erat. Est velit egestas dui id ornare.\"}},{\"id\":\"vZcdXQ5Kg8\",\"type\":\"header\",\"data\":{\"text\":\"Sample 1\",\"level\":2}},{\"id\":\"5uZtSZU-1q\",\"type\":\"paragraph\",\"data\":{\"text\":\"Tellus cras adipiscing enim eu. Id diam maecenas ultricies mi eget mauris pharetra et ultrices. Egestas purus viverra accumsan in nisl nisi scelerisque eu. Elementum facilisis leo vel fringilla. Purus sit amet volutpat consequat mauris.\"}},{\"id\":\"0kDVEXP9oB\",\"type\":\"header\",\"data\":{\"text\":\"Sample 2\",\"level\":3}},{\"id\":\"Bc1QpQcBOU\",\"type\":\"paragraph\",\"data\":{\"text\":\"Nisl vel pretium lectus quam id leo in. Nisi porta lorem mollis aliquam ut. Sit amet nisl purus in mollis nunc. Viverra vitae congue eu consequat ac felis donec. Ultrices dui sapien eget mi proin sed libero enim. Id aliquet lectus proin nibh nisl condimentum id venenatis a. Facilisis gravida neque convallis a cras semper auctor neque.\"}}],\"version\":\"2.26.5\"}','2022-12-18 11:02:51','2023-03-07 16:37:14',64,'63e8a51884a25','Quam nulla porttitor massa id. Vel facilisis volutpat est velit egestas dui. Tortor at risus viverra adipiscing at in. Mauris cursus mattis molestie a iaculis at. Sapien pellentesque habitant morbi tristique senectus et netus.','abhinavr'),
(5,'10 Best Campsites in Europe','{\"time\":1676453298804,\"blocks\":[{\"id\":\"ahJhWqnk6A\",\"type\":\"paragraph\",\"data\":{\"text\":\"At risus viverra adipiscing at in. In metus vulputate eu scelerisque felis imperdiet proin. Lectus mauris ultrices eros in cursus turpis massa tincidunt dui. Tempus iaculis urna id volutpat. Sollicitudin ac orci phasellus egestas. Eget felis eget nunc lobortis.\"}},{\"id\":\"eQoNSzfTTd\",\"type\":\"paragraph\",\"data\":{\"text\":\"Feugiat sed lectus vestibulum mattis ullamcorper velit sed. Amet venenatis urna cursus eget nunc scelerisque viverra. In massa tempor nec feugiat nisl pretium fusce.\"}},{\"id\":\"3gu4Jvn3UZ\",\"type\":\"paragraph\",\"data\":{\"text\":\"Felis imperdiet proin fermentum leo vel. Id venenatis a condimentum vitae sapien pellentesque habitant morbi tristique. Sociis natoque penatibus et magnis dis parturient montes. Cursus vitae congue mauris rhoncus aenean vel. Neque convallis a cras semper auctor. Neque egestas congue quisque egestas. Molestie ac feugiat sed lectus vestibulum. Aliquet risus feugiat in ante.\"}},{\"id\":\"IlaymJXMIm\",\"type\":\"paragraph\",\"data\":{\"text\":\"Eu turpis egestas pretium aenean. Dictumst vestibulum rhoncus est pellentesque elit ullamcorper dignissim cras. Et malesuada fames ac turpis. Mattis ullamcorper velit sed ullamcorper morbi tincidunt. Sed blandit libero volutpat sed cras.\"}},{\"id\":\"D-lqEwBPaz\",\"type\":\"paragraph\",\"data\":{\"text\":\"Dignissim suspendisse in est ante in nibh. Posuere ac ut consequat semper viverra nam. Eu lobortis elementum nibh tellus molestie nunc non blandit massa. Adipiscing commodo elit at imperdiet dui accumsan sit. Senectus et netus et malesuada. Metus aliquam eleifend mi in nulla. Libero id faucibus nisl tincidunt eget nullam.\"}},{\"id\":\"WXuB7COwsk\",\"type\":\"paragraph\",\"data\":{\"text\":\"Facilisis volutpat est velit egestas dui id ornare arcu. Pulvinar pellentesque habitant morbi tristique senectus. Cursus vitae congue mauris rhoncus aenean vel elit scelerisque mauris. Libero justo laoreet sit amet cursus sit amet dictum sit. Vitae suscipit tellus mauris a diam maecenas sed enim. Diam volutpat commodo sed egestas egestas. Amet risus nullam eget felis eget nunc lobortis.\"}}],\"version\":\"2.26.5\"}','2023-01-20 15:08:27','2023-02-15 09:28:18',61,'campsites-europe','At risus viverra adipiscing at in. In metus vulputate eu scelerisque felis imperdiet proin. Lectus mauris ultrices eros in cursus turpis massa tincidunt dui. Tempus iaculis urna id volutpat. Sollicitudin ac orci phasellus egestas. Eget felis eget nunc lobortis.','testuser'),
(6,'10 best travel books','{\"time\":1676453410727,\"blocks\":[{\"id\":\"gT1jW4fpY5\",\"type\":\"paragraph\",\"data\":{\"text\":\"Sed velit dignissim sodales ut eu sem integer vitae. Fusce id velit ut tortor pretium viverra suspendisse potenti. Orci phasellus egestas tellus rutrum tellus pellentesque eu tincidunt tortor.\"}},{\"id\":\"-rFYMXDbr-\",\"type\":\"paragraph\",\"data\":{\"text\":\"Metus aliquam eleifend mi in nulla posuere sollicitudin. Elit ullamcorper dignissim cras tincidunt. Donec adipiscing tristique risus nec feugiat in. Tellus cras adipiscing enim eu turpis egestas pretium aenean pharetra. Vestibulum sed arcu non odio euismod. Odio aenean sed adipiscing diam donec.\"}},{\"id\":\"T5KgVobPSp\",\"type\":\"paragraph\",\"data\":{\"text\":\"Nunc mi ipsum faucibus vitae aliquet nec ullamcorper sit amet. Diam quam nulla porttitor massa id neque aliquam vestibulum morbi. Viverra mauris in aliquam sem. Tincidunt augue interdum velit euismod in pellentesque. Vel facilisis volutpat est velit. Pellentesque sit amet porttitor eget.\"}},{\"id\":\"BgEZ9GYXgy\",\"type\":\"paragraph\",\"data\":{\"text\":\"Quam pellentesque nec nam aliquam sem et. Diam quam nulla porttitor massa. Et odio pellentesque diam volutpat commodo sed egestas egestas. Et netus et malesuada fames ac turpis egestas integer. Enim nec dui nunc mattis. Adipiscing commodo elit at imperdiet dui accumsan sit amet nulla. Risus nullam eget felis eget. Quis commodo odio aenean sed adipiscing diam donec adipiscing. Vitae aliquet nec ullamcorper sit amet risus.\"}},{\"id\":\"kocZKK7m29\",\"type\":\"paragraph\",\"data\":{\"text\":\"Blandit cursus risus at ultrices. Id aliquet lectus proin nibh nisl. Elementum facilisis leo vel fringilla est ullamcorper eget nulla. Pharetra pharetra massa massa ultricies. Quis imperdiet massa tincidunt nunc pulvinar. Mauris vitae ultricies leo integer malesuada nunc. Commodo viverra maecenas accumsan lacus vel facilisis.\"}},{\"id\":\"A1CXSeFL_t\",\"type\":\"paragraph\",\"data\":{\"text\":\"Urna nec tincidunt praesent semper feugiat nibh. Venenatis cras sed felis eget velit aliquet sagittis. Suspendisse faucibus interdum posuere lorem ipsum dolor sit amet. Sed id semper risus in hendrerit gravida.\"}}],\"version\":\"2.26.5\"}','2023-01-31 09:25:10','2023-02-15 09:30:10',69,'travel-books','Sed velit dignissim sodales ut eu sem integer vitae. Fusce id velit ut tortor pretium viverra suspendisse potenti.','abhinavr'),
(8,'15 Tips to make your travel photos pop','{\"time\":1676453487703,\"blocks\":[{\"id\":\"ytOMY-T80K\",\"type\":\"paragraph\",\"data\":{\"text\":\"Et sollicitudin ac orci phasellus. Consequat mauris nunc congue nisi vitae. Aliquam id diam maecenas ultricies mi eget mauris. Mauris vitae ultricies leo integer malesuada nunc vel. Lorem ipsum dolor sit amet consectetur adipiscing. Consectetur adipiscing elit pellentesque habitant.\"}},{\"id\":\"7GiM6sw8Mo\",\"type\":\"paragraph\",\"data\":{\"text\":\"At quis risus sed vulputate odio. Faucibus a pellentesque sit amet porttitor eget dolor. Sit amet cursus sit amet dictum sit amet. Vitae nunc sed velit dignissim sodales ut eu sem. Adipiscing elit pellentesque habitant morbi tristique senectus. Dolor sit amet consectetur adipiscing elit duis tristique.\"}},{\"id\":\"xRAfvCNZam\",\"type\":\"paragraph\",\"data\":{\"text\":\"Curabitur vitae nunc sed velit dignissim sodales. Sit amet nulla facilisi morbi tempus. Congue nisi vitae suscipit tellus mauris a. Nec feugiat nisl pretium fusce. Fermentum leo vel orci porta non.\"}},{\"id\":\"pONcZQ5Y5M\",\"type\":\"paragraph\",\"data\":{\"text\":\"Aliquam ultrices sagittis orci a scelerisque purus semper eget. Arcu odio ut sem nulla pharetra diam sit. Vel quam elementum pulvinar etiam non quam. Gravida in fermentum et sollicitudin ac orci.\"}},{\"id\":\"kAMoRcYSW8\",\"type\":\"paragraph\",\"data\":{\"text\":\"Condimentum lacinia quis vel eros donec ac odio tempor orci. Lobortis scelerisque fermentum dui faucibus. Lectus sit amet est placerat in egestas. Et sollicitudin ac orci phasellus egestas. Mauris vitae ultricies leo integer malesuada nunc vel risus. Eget gravida cum sociis natoque penatibus et magnis dis. In dictum non consectetur a erat nam.\"}}],\"version\":\"2.26.5\"}','2023-02-01 09:26:20','2023-02-15 09:31:27',70,'travel-photo-tips','Et sollicitudin ac orci phasellus. Consequat mauris nunc congue nisi vitae. Aliquam id diam maecenas ultricies mi eget mauris. Mauris vitae ultricies leo integer malesuada nunc vel. Lorem ipsum dolor sit amet consectetur adipiscing. Consectetur adipiscing elit pellentesque habitant.','abhinavr'),
(9,'How to find good budget hotel','{\"time\":1676453586647,\"blocks\":[{\"id\":\"EkMhiZCaqt\",\"type\":\"paragraph\",\"data\":{\"text\":\"Eget gravida cum sociis natoque penatibus et magnis. Consequat mauris nunc congue nisi vitae suscipit tellus mauris a. Egestas sed tempus urna et pharetra pharetra massa massa. Elementum facilisis leo vel fringilla est ullamcorper eget nulla. In hac habitasse platea dictumst quisque sagittis.\"}},{\"id\":\"0YGMkc-XDQ\",\"type\":\"paragraph\",\"data\":{\"text\":\"Velit laoreet id donec ultrices. Eros donec ac odio tempor orci dapibus ultrices. Ornare lectus sit amet est. Vitae auctor eu augue ut lectus. Nisl purus in mollis nunc sed id semper. Mauris augue neque gravida in fermentum. Vel fringilla est ullamcorper eget nulla. Venenatis cras sed felis eget velit. Consectetur libero id faucibus nisl tincidunt.\"}},{\"id\":\"evIIsDz-_b\",\"type\":\"paragraph\",\"data\":{\"text\":\"Fusce id velit ut tortor. Amet est placerat in egestas erat imperdiet sed euismod nisi. Egestas tellus rutrum tellus pellentesque eu tincidunt. At lectus urna duis convallis convallis.\"}},{\"id\":\"KPQEXVkBoS\",\"type\":\"paragraph\",\"data\":{\"text\":\"Purus non enim praesent elementum facilisis leo vel. Non nisi est sit amet facilisis magna etiam tempor. Scelerisque felis imperdiet proin fermentum. Etiam erat velit scelerisque in dictum non. At varius vel pharetra vel turpis. Aliquam sem et tortor consequat id. Lorem sed risus ultricies tristique nulla aliquet enim.\"}},{\"id\":\"C3eK10HVhv\",\"type\":\"paragraph\",\"data\":{\"text\":\"Commodo odio aenean sed adipiscing diam donec. Lectus magna fringilla urna porttitor rhoncus dolor purus non. Purus in massa tempor nec feugiat nisl pretium fusce id. Varius quam quisque id diam vel quam. Placerat orci nulla pellentesque dignissim enim sit amet. Pharetra convallis posuere morbi leo urna molestie at elementum eu. Nec nam aliquam sem et tortor consequat id porta nibh. Lacinia quis vel eros donec ac.\"}}],\"version\":\"2.26.5\"}','2023-02-03 09:26:55','2023-02-15 09:33:06',60,'63e8b0dfe3615','Eget gravida cum sociis natoque penatibus et magnis. Consequat mauris nunc congue nisi vitae suscipit tellus mauris a. Egestas sed tempus urna et pharetra pharetra massa massa. Elementum facilisis leo vel fringilla est ullamcorper eget nulla. In hac habitasse platea dictumst quisque sagittis.','testuser'),
(10,'How to save money for travel','{\"time\":1676453665034,\"blocks\":[{\"id\":\"UmcQllPWYa\",\"type\":\"paragraph\",\"data\":{\"text\":\"Mauris in aliquam sem fringilla ut morbi tincidunt augue interdum. Eu tincidunt tortor aliquam nulla facilisi cras fermentum odio. Viverra nam libero justo laoreet. Non consectetur a erat nam at lectus urna duis convallis.\"}},{\"id\":\"0LC0rSbJoS\",\"type\":\"paragraph\",\"data\":{\"text\":\"Orci a scelerisque purus semper eget duis at tellus. Etiam sit amet nisl purus in mollis nunc sed id. Et ultrices neque ornare aenean euismod elementum nisi quis. Turpis massa sed elementum tempus egestas sed sed risus pretium. Ut tristique et egestas quis ipsum suspendisse ultrices.\"}},{\"id\":\"9fo0600zhH\",\"type\":\"paragraph\",\"data\":{\"text\":\"Mus mauris vitae ultricies leo integer malesuada nunc. Neque volutpat ac tincidunt vitae. Cras pulvinar mattis nunc sed blandit libero volutpat sed cras. Eu feugiat pretium nibh ipsum consequat nisl vel. At elementum eu facilisis sed. Eget est lorem ipsum dolor sit. Nam at lectus urna duis convallis convallis tellus id.\"}},{\"id\":\"hcIsWor9qj\",\"type\":\"paragraph\",\"data\":{\"text\":\"Ac ut consequat semper viverra. Etiam dignissim diam quis enim lobortis. Et ligula ullamcorper malesuada proin libero. Semper quis lectus nulla at volutpat diam. Risus nec feugiat in fermentum posuere urna nec tincidunt. Mi bibendum neque egestas congue quisque egestas diam.\"}},{\"id\":\"QnRg5HOh-w\",\"type\":\"paragraph\",\"data\":{\"text\":\"Interdum posuere lorem ipsum dolor sit amet consectetur adipiscing elit. Vel orci porta non pulvinar neque. Scelerisque eu ultrices vitae auctor eu augue ut. Eget nunc scelerisque viverra mauris in aliquam sem fringilla ut. Tincidunt augue interdum velit euismod in. Porta non pulvinar neque laoreet suspendisse interdum consectetur libero. At lectus urna duis convallis convallis tellus id interdum velit. Lorem donec massa sapien faucibus et molestie ac feugiat. Sodales ut etiam sit amet.\"}}],\"version\":\"2.26.5\"}','2023-02-04 09:28:23','2023-02-15 09:34:25',68,'63e8b13706b8e','Mauris in aliquam sem fringilla ut morbi tincidunt augue interdum. Eu tincidunt tortor aliquam nulla facilisi cras fermentum odio. Viverra nam libero justo laoreet. Non consectetur a erat nam at lectus urna duis convallis.','abhinavr'),
(11,'Best Hiking routes in the Himalayas','{\"time\":1676453717594,\"blocks\":[{\"id\":\"4iPsgBpWfK\",\"type\":\"paragraph\",\"data\":{\"text\":\"Consectetur purus ut faucibus pulvinar. In metus vulputate eu scelerisque. Adipiscing elit pellentesque habitant morbi tristique. Non nisi est sit amet facilisis magna etiam tempor. In mollis nunc sed id semper. Nunc congue nisi vitae suscipit tellus mauris a diam maecenas. Vulputate odio ut enim blandit volutpat maecenas volutpat blandit aliquam.\"}},{\"id\":\"WjXjKkm4U3\",\"type\":\"paragraph\",\"data\":{\"text\":\"Porttitor eget dolor morbi non arcu risus quis. In ornare quam viverra orci sagittis eu volutpat odio. Id diam vel quam elementum pulvinar etiam non. Morbi blandit cursus risus at ultrices mi tempus imperdiet nulla. Integer eget aliquet nibh praesent tristique magna sit amet purus. Sem viverra aliquet eget sit amet. Habitant morbi tristique senectus et. Cras ornare arcu dui vivamus arcu. Sit amet volutpat consequat mauris. Adipiscing vitae proin sagittis nisl rhoncus mattis rhoncus urna neque. Mi quis hendrerit dolor magna eget est lorem ipsum dolor.\"}},{\"id\":\"GbRYIuM7Vw\",\"type\":\"paragraph\",\"data\":{\"text\":\"Mauris in aliquam sem fringilla ut morbi tincidunt augue interdum. Eu tincidunt tortor aliquam nulla facilisi cras fermentum odio. Viverra nam libero justo laoreet. Non consectetur a erat nam at lectus urna duis convallis.\"}},{\"id\":\"yG5hMbQM-_\",\"type\":\"paragraph\",\"data\":{\"text\":\"Orci a scelerisque purus semper eget duis at tellus. Etiam sit amet nisl purus in mollis nunc sed id. Et ultrices neque ornare aenean euismod elementum nisi quis. Turpis massa sed elementum tempus egestas sed sed risus pretium. Ut tristique et egestas quis ipsum suspendisse ultrices.\"}},{\"id\":\"EOa0YCQV4z\",\"type\":\"paragraph\",\"data\":{\"text\":\"Mus mauris vitae ultricies leo integer malesuada nunc. Neque volutpat ac tincidunt vitae. Cras pulvinar mattis nunc sed blandit libero volutpat sed cras. Eu feugiat pretium nibh ipsum consequat nisl vel. At elementum eu facilisis sed. Eget est lorem ipsum dolor sit. Nam at lectus urna duis convallis convallis tellus id.\"}}],\"version\":\"2.26.5\"}','2023-02-05 09:28:50','2023-02-15 09:35:17',64,'63e8b1523efaa','Consectetur purus ut faucibus pulvinar. In metus vulputate eu scelerisque.','abhinavr'),
(12,'How to learn a foreign language','{\"time\":1676456298264,\"blocks\":[{\"id\":\"ZKc3M-5_0A\",\"type\":\"paragraph\",\"data\":{\"text\":\"Sit amet commodo nulla facilisi nullam vehicula ipsum. Magna fermentum iaculis eu non diam phasellus vestibulum lorem sed. Neque egestas congue quisque egestas diam in. Odio morbi quis commodo odio aenean sed adipiscing diam. Enim nunc faucibus a pellentesque. Fames ac turpis egestas maecenas pharetra convallis posuere. Suspendisse in est ante in. Sollicitudin aliquam ultrices sagittis orci. Arcu cursus euismod quis viverra nibh cras. Ut venenatis tellus in metus vulputate.\"}},{\"id\":\"OLuHWHiRjc\",\"type\":\"paragraph\",\"data\":{\"text\":\"Molestie ac feugiat sed lectus vestibulum mattis ullamcorper velit. Varius morbi enim nunc faucibus a. Mauris pharetra et ultrices neque ornare. Elit at imperdiet dui accumsan sit. Dis parturient montes nascetur ridiculus mus. Hac habitasse platea dictumst vestibulum rhoncus est pellentesque. Rhoncus mattis rhoncus urna neque. A iaculis at erat pellentesque adipiscing commodo elit at. Diam sollicitudin tempor id eu nisl nunc. Dictumst vestibulum rhoncus est pellentesque.\"}},{\"id\":\"DaGA_yzmcq\",\"type\":\"paragraph\",\"data\":{\"text\":\"Dolor morbi non arcu risus quis varius quam quisque id. Enim diam vulputate ut pharetra. Vel orci porta non pulvinar neque laoreet suspendisse interdum. Convallis a cras semper auctor neque vitae tempus quam pellentesque. Posuere lorem ipsum dolor sit amet consectetur adipiscing elit duis.\"}},{\"id\":\"7ZX_vT_ry3\",\"type\":\"paragraph\",\"data\":{\"text\":\"Egestas tellus rutrum tellus pellentesque eu. Adipiscing diam donec adipiscing tristique. Nulla malesuada pellentesque elit eget gravida cum sociis natoque penatibus. Sed viverra tellus in hac habitasse platea dictumst. Sed velit dignissim sodales ut eu sem integer vitae justo.\"}},{\"id\":\"mezRwBlXcn\",\"type\":\"paragraph\",\"data\":{\"text\":\"Phasellus egestas tellus rutrum tellus. Enim praesent elementum facilisis leo vel fringilla est. Auctor eu augue ut lectus arcu bibendum at varius. Purus faucibus ornare suspendisse sed nisi lacus sed. At tempor commodo ullamcorper a lacus.\"}}],\"version\":\"2.26.5\"}','2023-02-08 09:30:33','2023-02-15 10:18:18',62,'63e8b1b994970','Sit amet commodo nulla facilisi nullam vehicula ipsum. Magna fermentum iaculis eu non diam phasellus vestibulum lorem sed.','abhinavr'),
(13,'10 Best Lightweight Travel Cameras','{\"time\":1676456603419,\"blocks\":[{\"id\":\"i1FWq85i9C\",\"type\":\"paragraph\",\"data\":{\"text\":\"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Tristique et egestas quis ipsum. Vel fringilla est ullamcorper eget nulla.\"}},{\"id\":\"iMb4f9RbAr\",\"type\":\"paragraph\",\"data\":{\"text\":\"Non curabitur gravida arcu ac tortor dignissim convallis aenean et. Sit amet facilisis magna etiam tempor orci eu lobortis. Ultricies mi quis hendrerit dolor. Amet consectetur adipiscing elit duis tristique sollicitudin nibh sit amet. Rutrum quisque non tellus orci ac auctor.\"}},{\"id\":\"ws4FUuA0JF\",\"type\":\"paragraph\",\"data\":{\"text\":\"Egestas integer eget aliquet nibh praesent tristique magna sit. Dui faucibus in ornare quam viverra orci sagittis.\"}},{\"id\":\"vb3f01MLIZ\",\"type\":\"paragraph\",\"data\":{\"text\":\"Nulla facilisi cras fermentum odio eu feugiat pretium. Id consectetur purus ut faucibus pulvinar elementum. Nulla porttitor massa id neque. Morbi tristique senectus et netus et malesuada fames ac.\"}},{\"id\":\"5PQQYUnxpk\",\"type\":\"paragraph\",\"data\":{\"text\":\"Scelerisque viverra mauris in aliquam sem fringilla ut morbi. Eu lobortis elementum nibh tellus molestie nunc. Purus ut faucibus pulvinar elementum integer enim neque volutpat. Adipiscing diam donec adipiscing tristique risus nec feugiat in. Ipsum consequat nisl vel pretium. Id ornare arcu odio ut sem nulla pharetra. Cursus turpis massa tincidunt dui ut ornare. Odio aenean sed adipiscing diam donec.\"}},{\"id\":\"_7KVIP4VY2\",\"type\":\"paragraph\",\"data\":{\"text\":\"Felis donec et odio pellentesque diam volutpat commodo sed egestas. Congue nisi vitae suscipit tellus mauris a diam. Aliquam ut porttitor leo a diam sollicitudin. Est ultricies integer quis auctor. At tempor commodo ullamcorper a lacus vestibulum sed arcu non.\"}},{\"id\":\"-cwXHSbUux\",\"type\":\"paragraph\",\"data\":{\"text\":\"Elit ut aliquam purus sit amet luctus venenatis lectus magna. Sed adipiscing diam donec adipiscing tristique risus. Orci phasellus egestas tellus rutrum tellus.\"}}],\"version\":\"2.26.5\"}','2023-02-10 09:30:51','2023-02-15 10:23:23',71,'63e8b1cb80214','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','testuser'),
(14,'How to plan a road trip','{\"time\":1676456672600,\"blocks\":[{\"id\":\"M3XH-ObYFQ\",\"type\":\"paragraph\",\"data\":{\"text\":\"A pellentesque sit amet porttitor. Nisl tincidunt eget nullam non nisi est sit amet. Consequat interdum varius sit amet. Ornare lectus sit amet est placerat in. Mauris sit amet massa vitae tortor. Aliquam id diam maecenas ultricies mi eget mauris. Auctor neque vitae tempus quam.\"}},{\"id\":\"I38ftf5qQk\",\"type\":\"paragraph\",\"data\":{\"text\":\"Consectetur purus ut faucibus pulvinar elementum. Amet volutpat consequat mauris nunc congue nisi. Urna nec tincidunt praesent semper feugiat nibh sed pulvinar. Nibh ipsum consequat nisl vel. Libero volutpat sed cras ornare arcu dui vivamus arcu. At erat pellentesque adipiscing commodo elit.\"}},{\"id\":\"RT279hb_wj\",\"type\":\"paragraph\",\"data\":{\"text\":\"Arcu cursus vitae congue mauris rhoncus aenean. Eleifend mi in nulla posuere sollicitudin aliquam ultrices sagittis orci.\"}},{\"id\":\"Qe7BZEm2JG\",\"type\":\"paragraph\",\"data\":{\"text\":\"Adipiscing vitae proin sagittis nisl rhoncus mattis rhoncus. Feugiat nisl pretium fusce id velit. Est velit egestas dui id ornare arcu odio. Quis eleifend quam adipiscing vitae proin sagittis nisl rhoncus. Imperdiet massa tincidunt nunc pulvinar sapien et ligula. Molestie nunc non blandit massa enim nec.\"}},{\"id\":\"IV5B4SIfkW\",\"type\":\"paragraph\",\"data\":{\"text\":\"Auctor elit sed vulputate mi. Varius morbi enim nunc faucibus a pellentesque sit amet. Viverra vitae congue eu consequat. Augue mauris augue neque gravida in fermentum et sollicitudin ac. Dolor morbi non arcu risus quis varius quam quisque. Ac placerat vestibulum lectus mauris ultrices eros in. Neque laoreet suspendisse interdum consectetur. Orci eu lobortis elementum nibh.\"}},{\"id\":\"Xto8oysUII\",\"type\":\"paragraph\",\"data\":{\"text\":\"A pellentesque sit amet porttitor. Nisl tincidunt eget nullam non nisi est sit amet. Consequat interdum varius sit amet. Ornare lectus sit amet est placerat in. Mauris sit amet massa vitae tortor. Aliquam id diam maecenas ultricies mi eget mauris. Auctor neque vitae tempus quam.\"}},{\"id\":\"Rhx7r0j_pP\",\"type\":\"paragraph\",\"data\":{\"text\":\"Consectetur purus ut faucibus pulvinar elementum. Amet volutpat consequat mauris nunc congue nisi. Urna nec tincidunt praesent semper feugiat nibh sed pulvinar. Nibh ipsum consequat nisl vel. Libero volutpat sed cras ornare arcu dui vivamus arcu. At erat pellentesque adipiscing commodo elit.\"}},{\"id\":\"vWV7u0Demm\",\"type\":\"paragraph\",\"data\":{\"text\":\"Arcu cursus vitae congue mauris rhoncus aenean. Eleifend mi in nulla posuere sollicitudin aliquam ultrices sagittis orci.\"}},{\"id\":\"n_NV0uhurO\",\"type\":\"paragraph\",\"data\":{\"text\":\"Adipiscing vitae proin sagittis nisl rhoncus mattis rhoncus. Feugiat nisl pretium fusce id velit. Est velit egestas dui id ornare arcu odio. Quis eleifend quam adipiscing vitae proin sagittis nisl rhoncus. Imperdiet massa tincidunt nunc pulvinar sapien et ligula. Molestie nunc non blandit massa enim nec.\"}},{\"id\":\"zscSfRQ3SG\",\"type\":\"paragraph\",\"data\":{\"text\":\"Auctor elit sed vulputate mi. Varius morbi enim nunc faucibus a pellentesque sit amet. Viverra vitae congue eu consequat. Augue mauris augue neque gravida in fermentum et sollicitudin ac. Dolor morbi non arcu risus quis varius quam quisque. Ac placerat vestibulum lectus mauris ultrices eros in. Neque laoreet suspendisse interdum consectetur. Orci eu lobortis elementum nibh.\"}}],\"version\":\"2.26.5\"}','2023-02-11 09:33:36','2023-02-15 10:24:32',67,'plan-road-trip','A pellentesque sit amet porttitor. Nisl tincidunt eget nullam non nisi est sit amet.','abhinavr'),
(15,'Where to stay in Munnar','{\"time\":1682970141642,\"blocks\":[{\"id\":\"edYY3dbRVI\",\"type\":\"paragraph\",\"data\":{\"text\":\"Ultrices tincidunt arcu non sodales neque sodales ut etiam. Vitae suscipit tellus mauris a diam maecenas sed enim. Ac felis donec et odio. Libero enim sed faucibus turpis in eu. Odio euismod lacinia at quis risus sed. Sodales ut eu sem integer vitae.\"}},{\"id\":\"Oykgvyq9sd\",\"type\":\"insertImage\",\"data\":{\"file\":{\"url\":\"http://php-cms.local:8084/uploads/fullsize/2023/02/travel-books.jpg\"},\"caption\":\"some image\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false}},{\"id\":\"3xQyV6lH31\",\"type\":\"insertImage\",\"data\":{\"file\":{\"url\":\"http://php-cms.local:8084/uploads/fullsize/2023/02/munnar-stay.jpg\"},\"caption\":\"tea plantations\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false}},{\"id\":\"caG0uW7iUF\",\"type\":\"paragraph\",\"data\":{\"text\":\"Turpis egestas pretium aenean pharetra magna ac. Habitant morbi tristique senectus et netus et. Arcu bibendum at varius vel pharetra vel turpis nunc eget. Etiam tempor orci eu lobortis. Phasellus egestas tellus rutrum tellus. Sit amet dictum sit amet justo donec enim diam vulputate. Semper feugiat nibh sed pulvinar proin gravida.\"}},{\"id\":\"QL2O9mt85X\",\"type\":\"paragraph\",\"data\":{\"text\":\"Quis ipsum suspendisse ultrices gravida dictum fusce ut placerat. In pellentesque massa placerat duis ultricies lacus. Sit amet purus gravida quis. Vitae tortor condimentum lacinia quis vel eros donec ac. Lectus vestibulum mattis ullamcorper velit sed ullamcorper morbi. Venenatis urna cursus eget nunc scelerisque.\"}},{\"id\":\"TajJz_F_UR\",\"type\":\"paragraph\",\"data\":{\"text\":\"Auctor augue mauris augue neque gravida in fermentum et. Sem nulla pharetra diam sit amet. Nec nam aliquam sem et tortor. Pharetra et ultrices neque ornare aenean euismod. Cras ornare arcu dui vivamus arcu felis bibendum ut. Faucibus pulvinar elementum integer enim neque volutpat ac tincidunt. Vel elit scelerisque mauris pellentesque pulvinar.\"}},{\"id\":\"EsgavuF7JN\",\"type\":\"paragraph\",\"data\":{\"text\":\"Quam quisque id diam vel. Pharetra magna ac placerat vestibulum lectus. Quisque sagittis purus sit amet. Purus in massa tempor nec feugiat. Vulputate sapien nec sagittis aliquam malesuada. Donec enim diam vulputate ut pharetra. Tempus egestas sed sed risus pretium quam vulputate dignissim suspendisse.\"}},{\"id\":\"xg4dlMcnjh\",\"type\":\"paragraph\",\"data\":{\"text\":\"Aliquam eleifend mi in nulla posuere sollicitudin. Turpis cursus in hac habitasse platea dictumst quisque sagittis purus. Ullamcorper a lacus vestibulum sed arcu. Convallis aenean et tortor at risus viverra. Tincidunt id aliquet risus feugiat. Suspendisse sed nisi lacus sed viverra.\"}}],\"version\":\"2.26.5\"}','2023-02-12 09:33:53','2023-05-01 19:42:21',67,'munnar-stay','Ultrices tincidunt arcu non sodales neque sodales ut etiam. Vitae suscipit tellus mauris a diam maecenas sed enim.','testuser'),
(23,'Sample Article','{\"time\":1683118191261,\"blocks\":[{\"id\":\"Ej5pe--v24\",\"type\":\"paragraph\",\"data\":{\"text\":\"Here is some content\"}},{\"id\":\"8o-dr9F8bp\",\"type\":\"list\",\"data\":{\"style\":\"ordered\",\"items\":[\"item 1\",\"item 2\",\"item 3\"]}},{\"id\":\"gXjtl6gjT1\",\"type\":\"header\",\"data\":{\"text\":\"Hello\",\"level\":2}},{\"id\":\"SsLQqpoSfD\",\"type\":\"image\",\"data\":{\"file\":{\"url\":\"../uploads/fullsize/2023/05/jammu-kashmir.jpg\"},\"caption\":\"lake view\",\"withBorder\":false,\"stretched\":false,\"withBackground\":false}},{\"id\":\"q1E1DiJcy2\",\"type\":\"insertImage\",\"data\":{\"file\":{\"url\":\"http://php-cms.local:8084/uploads/fullsize/2023/02/pexels-photomix-company-212372.jpg\"},\"caption\":\"photography\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false}}],\"version\":\"2.26.5\"}','2023-05-03 12:35:19','2023-05-03 12:49:51',75,'sample-post','Suspendisse potenti nullam ac tortor vitae purus faucibus ornare. Lorem sed risus ultricies tristique nulla aliquet. Vivamus at augue eget arcu dictum varius duis at consectetur. Vitae aliquet nec ullamcorper sit amet.','abhinavr');
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `upload_date` date DEFAULT NULL,
  `folder_path` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `images`
--

LOCK TABLES `images` WRITE;
/*!40000 ALTER TABLE `images` DISABLE KEYS */;
INSERT INTO `images` VALUES
(28,NULL,'igazu.jpg','image/jpeg','2023-02-09','2023/02','abhinavr'),
(29,NULL,'machu-picchu.jpg','image/jpeg','2023-02-09','2023/02','abhinavr'),
(31,'','yellowstone.jpg','image/jpeg','2023-02-09','2023/02','testuser'),
(34,NULL,'uluru.jpg','image/jpeg','2023-02-10','2023/02','abhinavr'),
(35,NULL,'svalbard.jpg','image/jpeg','2023-02-10','2023/02','testuser'),
(36,NULL,'scotland.jpg','image/jpeg','2023-02-10','2023/02','abhinavr'),
(37,NULL,'tasmania.jpg','image/jpeg','2023-02-10','2023/02','abhinavr'),
(38,NULL,'mt-everest.jpg','image/jpeg','2023-02-10','2023/02','abhinavr'),
(39,'','polynesia.jpg','image/jpeg','2023-02-11','2023/02','testuser'),
(44,NULL,'kerala.jpg','image/jpeg','2023-02-11','2023/02','testuser'),
(45,NULL,'serengeti.jpg','image/jpeg','2023-02-11','2023/02','abhinavr'),
(46,NULL,'ngorongoro.jpg','image/jpeg','2023-02-11','2023/02','testuser'),
(47,'Lake Wanaka, New Zealand','lake-wanaka.jpg','image/jpeg','2023-02-11','2023/02','abhinavr'),
(48,'jhjgasg ','Jungfraujoch.jpg','image/jpeg','2023-02-11','2023/02','abhinavr'),
(49,NULL,'patagonia.jpg','image/jpeg','2023-02-11','2023/02','testuser'),
(50,NULL,'kilimanjaro.jpg','image/jpeg','2023-02-11','2023/02','testuser'),
(51,NULL,'angkor.jpg','image/jpeg','2023-02-11','2023/02','abhinavr'),
(52,NULL,'hampi.jpg','image/jpeg','2023-02-13','2023/02','testuser'),
(53,NULL,'black-forest.jpg','image/jpeg','2023-02-13','2023/02','testuser'),
(56,NULL,'india-heritage.jpg','image/jpeg','2023-02-13','2023/02','testuser'),
(57,NULL,'mobile-app.jpg','image/jpeg','2023-02-15','2023/02','abhinavr'),
(58,NULL,'fall-colors.jpg','image/jpeg','2023-02-15','2023/02','abhinavr'),
(59,NULL,'skiing-himachal.jpg','image/jpeg','2023-02-15','2023/02','abhinavr'),
(60,NULL,'budget-hotel.jpg','image/jpeg','2023-02-15','2023/02','abhinavr'),
(61,NULL,'campsites.jpg','image/jpeg','2023-02-15','2023/02','abhinavr'),
(62,NULL,'foreign-language.jpg','image/jpeg','2023-02-15','2023/02','abhinavr'),
(63,NULL,'hiking.jpg','image/jpeg','2023-02-15','2023/02','abhinavr'),
(64,NULL,'hiking-backpack.jpg','image/jpeg','2023-02-15','2023/02','abhinavr'),
(65,NULL,'jammu-kashmir.jpg','image/jpeg','2023-02-15','2023/02','abhinavr'),
(66,NULL,'munnar-stay.jpg','image/jpeg','2023-02-15','2023/02','abhinavr'),
(67,NULL,'road-trip.jpg','image/jpeg','2023-02-15','2023/02','abhinavr'),
(68,NULL,'save-money.jpg','image/jpeg','2023-02-15','2023/02','abhinavr'),
(69,NULL,'travel-books.jpg','image/jpeg','2023-02-15','2023/02','abhinavr'),
(70,NULL,'travel-photos.jpg','image/jpeg','2023-02-15','2023/02','abhinavr'),
(71,'here is a camera','pexels-photomix-company-212372.jpg','image/jpeg','2023-02-15','2023/02','abhinavr'),
(73,NULL,'code-3.jpg','image/jpeg','2023-02-20','2023/02','abhinavr'),
(74,NULL,'code-4.jpg','image/jpeg','2023-02-20','2023/02','abhinavr'),
(75,NULL,'pexels-photomix-company-212372.jpg','image/jpeg','2023-05-03','2023/05',NULL),
(80,'Lake view','jammu-kashmir.jpg','image/jpeg','2023-05-03','2023/05','abhinavr');
/*!40000 ALTER TABLE `images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resource` varchar(255) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `access` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES
(1,'article','read','admin,editor,anonymous'),
(2,'article','create','admin,editor'),
(3,'article','update','admin,owner'),
(4,'article','delete','admin,owner'),
(5,'image','read','admin,editor,anonymous'),
(6,'image','create','admin,editor'),
(7,'image','update','admin,owner'),
(8,'image','delete','admin,owner'),
(9,'admin_user','read','admin'),
(10,'admin_user','create','none'),
(11,'admin_user','update','owner'),
(12,'admin_user','delete','none'),
(13,'user','read','admin,owner'),
(14,'user','create','admin'),
(15,'user','update','admin,owner'),
(16,'user','delete','admin'),
(17,'settings','read','admin, editor'),
(18,'settings','create','admin'),
(19,'settings','update','admin'),
(20,'settings','delete','admin');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option_key` varchar(255) DEFAULT NULL,
  `option_value` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES
(1,'site_title','CoolNomad'),
(2,'site_tagline','Travel stories from around the world.'),
(3,'thumbnail_size','768');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'abhinavr','$2y$10$BjwsIBzZujcLSdaul2kNAube8CaOLyMLAPKICM9RTaO9xDUErbxHu','abhinav@example.com','Abhinav','admin'),
(2,'testuser','$2y$10$Vxffo7c6t5tEB4eU.rG/4OhqmO7VjO1gHx.HER75kTYXunY1W0hP6','test@example.com','Tester','editor');
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

-- Dump completed on 2023-05-25 19:22:11
