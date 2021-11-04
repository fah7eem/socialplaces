CREATE TABLE `app_logs` (
  `rowid` int(11) NOT NULL AUTO_INCREMENT,
  `channel` varchar(45) DEFAULT NULL,
  `level` varchar(45) DEFAULT NULL,
  `message` longtext,
  `path` varchar(255) DEFAULT NULL,
  `time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`rowid`)
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `user` (
  `username` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`username`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- password1
INSERT INTO `user` (`username`, `password`) VALUES ('fseedat7@gmail.com', SHA2(concat('Password123', 'fseedat7@gmail.com'), 256));
