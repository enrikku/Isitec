

CREATE TABLE lessons (
    lessonId INT AUTO_INCREMENT PRIMARY KEY,
    courseId INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    videoURL VARCHAR(255) NULL DEFAULT NULL,
    resourceZip VARCHAR(255) NULL DEFAULT NULL,
    FOREIGN KEY (courseId) REFERENCES courses(courseId)
);


ALTER TABLE videos ADD COLUMN lessonId INT,
ADD FOREIGN KEY (lessonId) REFERENCES lessons(lessonId);

CREATE TABLE testimonials (
    testimonialId INT AUTO_INCREMENT PRIMARY KEY,
    courseId INT NOT NULL,
    userId INT NOT NULL,
    testimonial TEXT NOT NULL,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    FOREIGN KEY (courseId) REFERENCES courses(courseId),
    FOREIGN KEY (userId) REFERENCES users(iduser)
);

CREATE TABLE course_subscriptions (
    subscriptionId INT AUTO_INCREMENT PRIMARY KEY,
    userId INT NOT NULL,
    courseId INT NOT NULL,
    subscriptionDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userId) REFERENCES users(iduser),
    FOREIGN KEY (courseId) REFERENCES courses(courseId),
    UNIQUE KEY unique_subscription (userId, courseId)
);

