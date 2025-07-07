/*
 Navicat Premium Data Transfer

 Source Server         : laravel_3
 Source Server Type    : MySQL
 Source Server Version : 80030 (8.0.30)
 Source Host           : localhost:3306
 Source Schema         : uas_weblanjut

 Target Server Type    : MySQL
 Target Server Version : 80030 (8.0.30)
 File Encoding         : 65001

 Date: 06/07/2025 17:30:55
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for kantin
-- ----------------------------
DROP TABLE IF EXISTS `kantin`;
CREATE TABLE `kantin`  (
  `no` int NOT NULL AUTO_INCREMENT,
  `nama_warung` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `date` date NULL DEFAULT NULL,
  `deskripsi` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `alt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`no`) USING BTREE,
  INDEX `nama_warung`(`nama_warung` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kantin
-- ----------------------------
INSERT INTO `kantin` VALUES (1, 'Bu markima', '2025-06-03', 'Bagi pecinta kuliner Jepang, Warung Bu Markima adalah pilihan yang tepat.\r\n                          Di sini tersedia berbagai menu seperti chicken katsu, ramen, sushi roll sederhana,\r\n                          dan bento box yang cocok dengan selera lokal.\r\n                          Warung ini menyajikan pengalaman makan ala Jepang dengan harga terjangkau dan porsi pas untuk mahasiswa..', 'kantin/Japanese cafe.jpeg', 'Warung bu makima');
INSERT INTO `kantin` VALUES (2, 'Bu neneng', '2025-05-27', 'Nikmati cita rasa khas Sunda yang autentik di Warung Bu Neneng.\r\n                          Menghidangkan berbagai menu rumahan seperti nasi liwet, sayur asem, ayam goreng, tempe mendoan, dan sambal terasi segar. Dengan suasana yang sederhana dan ramah,\r\n                          warung ini cocok menjadi tempat makan siang favorit mahasiswa yang rindu masakan rumah..', 'kantin/download (6).jpeg', 'Warung bu neneng');
INSERT INTO `kantin` VALUES (3, 'Bu susi', '2025-04-28', 'Warung Susi menyajikan aneka masakan khas Jawa yang lezat dan menggugah selera.\r\n                          Mulai dari gudeg, rawon, tahu bacem, hingga oseng mercon,\r\n                          semua dimasak dengan resep tradisional dan penuh kehangatan.\r\n                          Rasa manis dan gurih yang menjadi ciri khas Jawa akan memanjakan lidahmu di setiap suapan.', 'kantin/download (8).jpeg', 'Warung bu susi');
INSERT INTO `kantin` VALUES (4, 'Pak Gendut', '2025-02-28', 'Warung Pak Gendut menyajikan masakan khas Minang yang terkenal akan kekayaan rempahnya. Menu andalannya seperti rendang, ayam pop, dan gulai otak selalu laris manis setiap hari. Suasana warung yang sederhana namun bersih membuat pelanggan betah makan di tempat.', 'kantin/makan padang.jpeg', 'Warung pak gendut');
INSERT INTO `kantin` VALUES (5, 'Mamih Kim', '2019-06-28', 'Warung Mamih Kim menawarkan sensasi makan ala Korea dengan cita rasa yang sudah disesuaikan dengan lidah lokal. Menu favorit seperti kimchi jjigae, tteokbokki, dan bulgogi disajikan dengan presentasi menarik dalam suasana cozy.', 'kantin/Korean food.jpeg', 'Warung mamih kim');
INSERT INTO `kantin` VALUES (6, 'Mbok Yem', '2025-02-12', 'Warung Mbok Yem menghadirkan cita rasa khas Bali dalam setiap menunya seperti ayam betutu, sate lilit, dan lawar. Dengan dekorasi bernuansa Bali, warung ini memberikan pengalaman makan yang tidak hanya enak tapi juga penuh budaya.', 'kantin/Sari Bundo Restaurant - Sanur, Bali.jpeg', 'Warung mbok yem');

-- ----------------------------
-- Table structure for keranjang
-- ----------------------------
DROP TABLE IF EXISTS `keranjang`;
CREATE TABLE `keranjang`  (
  `no` int NOT NULL AUTO_INCREMENT,
  `nama_warung` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `nama_makanan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `harga` decimal(10, 3) NULL DEFAULT NULL,
  `jumlah` int NULL DEFAULT NULL,
  `total` decimal(10, 3) NULL DEFAULT NULL,
  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`no`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 31 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of keranjang
-- ----------------------------

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu`  (
  `no` int NOT NULL AUTO_INCREMENT,
  `nama_makanan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `rate` decimal(3, 1) NULL DEFAULT NULL,
  `price` decimal(10, 3) NULL DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `nama_warung` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `alt_makanan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`no`) USING BTREE,
  INDEX `nama_warung`(`nama_warung` ASC) USING BTREE,
  CONSTRAINT `nama_warung` FOREIGN KEY (`nama_warung`) REFERENCES `kantin` (`nama_warung`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES (1, 'Seblak', 5.0, 21.000, 'cemilan', 'Bu neneng', 'cemilan/download__7_-removebg-preview.png', 'Seblak');
INSERT INTO `menu` VALUES (2, 'Nasi Goreng', 4.5, 25.000, 'makanan', 'Bu susi', 'makanan/7 Resep Nasi Goreng Enak untuk Dijual, Inspirasi Bisnis Rumahan Bunda.jpeg', 'Nasi Goreng');
INSERT INTO `menu` VALUES (3, 'Ayam katsu kari', 4.9, 19.000, 'makanan', 'Bu susi', 'makanan/chicken katsu curry - smelly lunchbox.jpeg', 'Ayam katsu kari');
INSERT INTO `menu` VALUES (4, 'Wonton Chili Oil', 4.8, 15.000, 'cemilan', 'Bu neneng', 'cemilan/WONTON CHILI OIL.jpeg', 'Wonton Chili Oil');
INSERT INTO `menu` VALUES (5, 'Mocha', 4.7, 11.000, 'minuman', 'Bu markima', 'minuman/Mocha recipe.jpeg', 'Mocha');
INSERT INTO `menu` VALUES (6, 'Strawberry Milkshake', 5.0, 13.000, 'minuman', 'Bu markima', 'minuman/How to Make a Strawberry Milkshake without Ice Cream.jpeg', 'Strawberry Milkshake');
INSERT INTO `menu` VALUES (7, 'Nasi Lemak', 4.6, 17.000, 'makanan', 'Bu susi', 'makanan/30 Best Restaurants in Singapore.jpeg', 'Nasi Lemak');
INSERT INTO `menu` VALUES (8, 'Es Campur Mutiara Kuah Santan', 4.6, 13.000, 'minuman', 'Mbok Yem', 'minuman/Es Campur Mutiara Kuah Santan.jpeg', 'Es Campur Mutiara Kuah Santan');
INSERT INTO `menu` VALUES (9, 'Korean Bibimbap', 4.5, 21.000, 'makanan', 'Mamih Kim', 'makanan/Korean Bibimbap Recipe_ A Delicious and Colorful Dish.jpeg', 'Korean Bibimbap');
INSERT INTO `menu` VALUES (10, 'Ramen pedas', 4.6, 15.000, 'cemilan', 'Mamih Kim', 'cemilan/6860de0366f95.jpeg', 'Ramen pedas');

-- ----------------------------
-- Table structure for testimoni
-- ----------------------------
DROP TABLE IF EXISTS `testimoni`;
CREATE TABLE `testimoni`  (
  `no` int NOT NULL AUTO_INCREMENT,
  `tanggal` date NULL DEFAULT NULL,
  `nama_akun` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `deskripsi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `nama_warung` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `img_user` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`no`) USING BTREE,
  INDEX `kt_warung`(`nama_warung` ASC) USING BTREE,
  CONSTRAINT `kt_warung` FOREIGN KEY (`nama_warung`) REFERENCES `menu` (`nama_warung`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of testimoni
-- ----------------------------
INSERT INTO `testimoni` VALUES (1, '2025-06-27', 'tommysatrio888@gmail.com', 'Nasi goreng di warung ini sangat enak sekali membuat saya ingin memesannya lagi dan lagi', 'Bu neneng', 'testimoni/7 Resep Nasi Goreng Enak untuk Dijual, Inspirasi Bisnis Rumahan Bunda.jpeg', 'user/Wiki-background.jpeg');
INSERT INTO `testimoni` VALUES (2, '2025-04-01', 'Ryuzen', 'Milkshakenya enak euy', 'Bu markima', 'testimoni/How to Make a Strawberry Milkshake without Ice Cream.jpeg', 'user/Komik-The-Player-Hides-His-Past.jpeg');
INSERT INTO `testimoni` VALUES (3, '2025-06-13', 'Kiryu', 'Es campurnya kemanisan bjir', 'Mbok Yem', 'testimoni/Es Campur Mutiara Kuah Santan.jpeg', 'user/SMLN.jpeg');
INSERT INTO `testimoni` VALUES (4, '2025-06-11', 'Sasami', 'Bimbimbapnya enak banget rasanya bikin nagih', 'Mamih Kim', 'testimoni/Korean Bibimbap Recipe_ A Delicious and Colorful Dish.jpeg', 'user/Komik-The-Great-Mage-Returns-After-4000-Years.jpeg');
INSERT INTO `testimoni` VALUES (5, '2025-06-19', 'Erina', 'Iced Oreonya enak bingitzz', 'Bu neneng', 'testimoni/Best Oreo Iced Coffee Recipe for a Creamy Sweet Treat.jpeg', 'user/sle.jpeg');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `no` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`no`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'admin@gmail.com', 'superadmin', '$2y$10$4XlblmGJp7KG7Xn06VXRGe3jyHaA7deBvsQyMahjSFDCIlFo.H2UC');
INSERT INTO `users` VALUES (2, 'tommysatrio36@gmail.com', 'tommy', '$2y$10$Twi5cZTfGU3OZKMMSdif3uvP1qzE/IvltM.fMsyvMSXAWpr//3Qi.');
INSERT INTO `users` VALUES (3, 'tommysatrio888@gmail.com', 'asd', '$2y$10$432t2K11t7ZOCGWXvt2lRuMPbfxbRvLoZ/ICOHm7x0UscnrtvvqHu');

SET FOREIGN_KEY_CHECKS = 1;
