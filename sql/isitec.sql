CREATE TABLE `course_subscriptions` (
  `subscriptionId` int NOT NULL AUTO_INCREMENT,
  `userId` int NOT NULL,
  `courseId` int NOT NULL,
  `subscriptionDate` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`subscriptionId`),
  UNIQUE KEY `unique_subscription` (`userId`,`courseId`),
  KEY `courseId` (`courseId`),
  CONSTRAINT `course_subscriptions_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`iduser`),
  CONSTRAINT `course_subscriptions_ibfk_2` FOREIGN KEY (`courseId`) REFERENCES `courses` (`courseId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin

CREATE TABLE `courses` (
  `courseId` int NOT NULL AUTO_INCREMENT,
  `userId` int DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `description` text COLLATE utf8mb4_bin,
  `publishDate` date DEFAULT NULL,
  `coverURL` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  PRIMARY KEY (`courseId`),
  KEY `userId` (`userId`),
  CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin


CREATE TABLE `coursetags` (
  `courseId` int NOT NULL,
  `tagId` int NOT NULL,
  PRIMARY KEY (`courseId`,`tagId`),
  KEY `tagId` (`tagId`),
  CONSTRAINT `coursetags_ibfk_1` FOREIGN KEY (`courseId`) REFERENCES `courses` (`courseId`),
  CONSTRAINT `coursetags_ibfk_2` FOREIGN KEY (`tagId`) REFERENCES `tags` (`tagId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin

CREATE TABLE `lessons` (
  `lessonId` int NOT NULL AUTO_INCREMENT,
  `courseId` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `description` text COLLATE utf8mb4_bin,
  `videoURL` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `resourceZip` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  PRIMARY KEY (`lessonId`),
  KEY `courseId` (`courseId`),
  CONSTRAINT `lessons_ibfk_1` FOREIGN KEY (`courseId`) REFERENCES `courses` (`courseId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin


CREATE TABLE `tags` (
  `tagId` int NOT NULL AUTO_INCREMENT,
  `tag` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`tagId`),
  UNIQUE KEY `tag` (`tag`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin


CREATE TABLE `testimonials` (
  `testimonialId` int NOT NULL AUTO_INCREMENT,
  `courseId` int NOT NULL,
  `userId` int NOT NULL,
  `testimonial` text COLLATE utf8mb4_bin NOT NULL,
  `rating` int DEFAULT NULL,
  PRIMARY KEY (`testimonialId`),
  KEY `courseId` (`courseId`),
  KEY `userId` (`userId`),
  CONSTRAINT `testimonials_ibfk_1` FOREIGN KEY (`courseId`) REFERENCES `courses` (`courseId`),
  CONSTRAINT `testimonials_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `users` (`iduser`),
  CONSTRAINT `testimonials_chk_1` CHECK ((`rating` between 1 and 5))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin


CREATE TABLE `users` (
  `iduser` int NOT NULL AUTO_INCREMENT,
  `mail` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `username` varchar(16) COLLATE utf8mb4_bin DEFAULT NULL,
  `passHash` varchar(60) COLLATE utf8mb4_bin DEFAULT NULL,
  `userFirstName` varchar(60) COLLATE utf8mb4_bin DEFAULT NULL,
  `userLastName` varchar(120) COLLATE utf8mb4_bin DEFAULT NULL,
  `creationDate` datetime DEFAULT NULL,
  `removeDate` datetime DEFAULT NULL,
  `lastSignIn` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `activationDate` datetime DEFAULT NULL,
  `activationCode` char(64) COLLATE utf8mb4_bin DEFAULT NULL,
  `resetPassExpiry` datetime DEFAULT NULL,
  `resetPassCode` char(64) COLLATE utf8mb4_bin DEFAULT NULL,
  PRIMARY KEY (`iduser`),
  UNIQUE KEY `mail` (`mail`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin


CREATE TABLE `videos` (
  `videoId` int NOT NULL AUTO_INCREMENT,
  `courseId` int DEFAULT NULL,
  `videoURL` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `lessonId` int DEFAULT NULL,
  PRIMARY KEY (`videoId`),
  KEY `courseId` (`courseId`),
  KEY `lessonId` (`lessonId`),
  CONSTRAINT `videos_ibfk_1` FOREIGN KEY (`courseId`) REFERENCES `courses` (`courseId`),
  CONSTRAINT `videos_ibfk_2` FOREIGN KEY (`lessonId`) REFERENCES `lessons` (`lessonId`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin


CREATE TABLE `votes` (
  `courseId` int NOT NULL,
  `likes` int DEFAULT '0',
  `dislikes` int DEFAULT '0',
  PRIMARY KEY (`courseId`),
  CONSTRAINT `votes_ibfk_1` FOREIGN KEY (`courseId`) REFERENCES `courses` (`courseId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin