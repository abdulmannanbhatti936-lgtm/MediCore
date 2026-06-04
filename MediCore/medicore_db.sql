CREATE DATABASE IF NOT EXISTS medicore_db;
USE medicore_db;

DROP TABLE IF EXISTS prescriptions;
DROP TABLE IF EXISTS appointments;
DROP TABLE IF EXISTS patients;
DROP TABLE IF EXISTS doctors;
DROP TABLE IF EXISTS directory_listings;
DROP TABLE IF EXISTS departments;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','doctor','patient') NOT NULL,
  phone VARCHAR(30),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE doctors (
  id INT AUTO_INCREMENT PRIMARY KEY,user_id INT NOT NULL,specialization VARCHAR(120) NOT NULL,
  qualification VARCHAR(150),experience_years INT DEFAULT 0,fee DECIMAL(10,2) DEFAULT 0,
  available_days VARCHAR(120),bio TEXT,profile_pic VARCHAR(255),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
CREATE TABLE patients (
  id INT AUTO_INCREMENT PRIMARY KEY,user_id INT NOT NULL,age INT,gender ENUM('Male','Female','Other') DEFAULT NULL,
  blood_group VARCHAR(8),address TEXT,emergency_contact VARCHAR(30),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
CREATE TABLE appointments (
  id INT AUTO_INCREMENT PRIMARY KEY,patient_id INT NOT NULL,doctor_id INT NOT NULL,appointment_date DATE NOT NULL,appointment_time TIME NOT NULL,
  status ENUM('pending','confirmed','cancelled','completed') DEFAULT 'pending',symptoms TEXT,notes TEXT,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
);
CREATE TABLE prescriptions (
  id INT AUTO_INCREMENT PRIMARY KEY,appointment_id INT NOT NULL,doctor_id INT NOT NULL,patient_id INT NOT NULL,medicines TEXT NOT NULL,instructions TEXT,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE,FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE,FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE
);
CREATE TABLE departments (
  id INT AUTO_INCREMENT PRIMARY KEY,name VARCHAR(120) NOT NULL,description TEXT,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE directory_listings (
  id INT AUTO_INCREMENT PRIMARY KEY,clinic_name VARCHAR(150),doctor_name VARCHAR(120),specialization VARCHAR(120),
  city VARCHAR(100),address TEXT,phone VARCHAR(30),website VARCHAR(255),created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_doctors_specialization ON doctors(specialization);
CREATE INDEX idx_appointments_status_date ON appointments(status, appointment_date);
CREATE INDEX idx_appointments_doctor_date ON appointments(doctor_id, appointment_date);
CREATE INDEX idx_directory_city_specialization ON directory_listings(city, specialization);

INSERT INTO users (id,name,email,password,role,phone) VALUES
(1,'System Admin','admin@medicore.com','$2y$10$DNaYxpEi7z2aSckJJG9qlOH/BCaxCg/v1G7sbKsWVz3PFkGQ.NsSy','admin','0300-1000000'),
(2,'Dr. Sarah Khan','sarah@medicore.com','$2y$10$iiw.O19fdGGktG6c/PrK6uga/NACPSGrPAbQQzKAwU6.lOozqIzEu','doctor','0301-1111111'),
(3,'Dr. John Ali','john@medicore.com','$2y$10$iiw.O19fdGGktG6c/PrK6uga/NACPSGrPAbQQzKAwU6.lOozqIzEu','doctor','0302-2222222'),
(4,'Dr. Emily Noor','emily@medicore.com','$2y$10$iiw.O19fdGGktG6c/PrK6uga/NACPSGrPAbQQzKAwU6.lOozqIzEu','doctor','0303-3333333'),
(5,'Ahmed Raza','patient1@medicore.com','$2y$10$uvEqtEB/OytbxrrNBwRSaubUkpRng8U.Q4aZuDXTyv4CbJ4D8rYKW','patient','0304-4444444'),
(6,'Sana Tariq','patient2@medicore.com','$2y$10$uvEqtEB/OytbxrrNBwRSaubUkpRng8U.Q4aZuDXTyv4CbJ4D8rYKW','patient','0305-5555555'),
(7,'Bilal Haider','patient3@medicore.com','$2y$10$uvEqtEB/OytbxrrNBwRSaubUkpRng8U.Q4aZuDXTyv4CbJ4D8rYKW','patient','0306-6666666'),
(8,'Hina Aslam','patient4@medicore.com','$2y$10$uvEqtEB/OytbxrrNBwRSaubUkpRng8U.Q4aZuDXTyv4CbJ4D8rYKW','patient','0307-7777777'),
(9,'Usman Malik','patient5@medicore.com','$2y$10$uvEqtEB/OytbxrrNBwRSaubUkpRng8U.Q4aZuDXTyv4CbJ4D8rYKW','patient','0308-8888888');
INSERT INTO doctors VALUES
(1,2,'Cardiology','MBBS, FCPS',12,3500,'Mon,Wed,Fri','Heart and vascular specialist',''),
(2,3,'Dermatology','MBBS, MCPS',8,2800,'Tue,Thu,Sat','Skin and hair treatment expert',''),
(3,4,'Neurology','MBBS, FCPS Neurology',15,4200,'Mon,Tue,Thu','Neurology consultant','');
INSERT INTO patients VALUES
(1,5,30,'Male','O+','Johar Town, Lahore','0300-9000001'),
(2,6,27,'Female','A+','Gulberg, Lahore','0300-9000002'),
(3,7,44,'Male','B+','DHA, Lahore','0300-9000003'),
(4,8,35,'Female','AB+','Model Town, Lahore','0300-9000004'),
(5,9,52,'Male','O-','Cantt, Lahore','0300-9000005');
INSERT INTO appointments VALUES
(1,1,1,CURDATE(),'10:00:00','confirmed','Chest discomfort','',NOW()),
(2,2,1,CURDATE(),'11:00:00','pending','Palpitations','',NOW()),
(3,3,2,DATE_SUB(CURDATE(),INTERVAL 3 DAY),'12:00:00','completed','Severe acne','Review after 2 weeks',NOW()),
(4,4,3,CURDATE(),'14:00:00','confirmed','Migraine episodes','',NOW()),
(5,5,2,DATE_SUB(CURDATE(),INTERVAL 1 DAY),'15:00:00','cancelled','Skin allergy','Patient cancelled',NOW()),
(6,1,3,DATE_ADD(CURDATE(),INTERVAL 1 DAY),'10:30:00','pending','Dizziness','',NOW()),
(7,2,2,DATE_ADD(CURDATE(),INTERVAL 2 DAY),'09:30:00','pending','Hair fall','',NOW()),
(8,3,1,DATE_SUB(CURDATE(),INTERVAL 10 DAY),'09:00:00','completed','Blood pressure issue','Stable now',NOW()),
(9,4,3,DATE_ADD(CURDATE(),INTERVAL 3 DAY),'16:00:00','pending','Sleep disorder','',NOW()),
(10,5,1,DATE_ADD(CURDATE(),INTERVAL 4 DAY),'13:00:00','pending','Breathing issue','',NOW());
INSERT INTO prescriptions VALUES
(1,3,2,3,'Doxycycline 100mg once daily','Continue 10 days',NOW()),
(2,8,1,3,'Amlodipine 5mg','Take every night',NOW()),
(3,4,3,4,'Paracetamol 500mg','Use on severe headache',NOW());
INSERT INTO departments (name,description) VALUES
('Cardiology','Heart and blood vessel care'),('Dermatology','Skin and hair health'),('Neurology','Brain and nerve care'),
('Pediatrics','Child healthcare'),('Orthopedics','Bone and joint treatment');
INSERT INTO directory_listings (clinic_name,doctor_name,specialization,city,address,phone,website) VALUES
('Atlas Heart Center','Dr. Sarah Khan','Cardiology','Lahore','Main Boulevard Gulberg','042-1111111','https://atlasdental.pk/'),
('Skin Renew Clinic','Dr. John Ali','Dermatology','Karachi','Clifton Block 5','021-2222222','https://example.com/skin-renew'),
('Neuro Life Hospital','Dr. Emily Noor','Neurology','Islamabad','Blue Area Sector F','051-3333333','https://example.com/neuro-life'),
('City Family Clinic','Dr. Mark Hasan','General Medicine','Faisalabad','Susan Road','041-4444444','https://example.com/city-family'),
('Kids First Care','Dr. Sana Waheed','Pediatrics','Multan','Cantt Road','061-5555555','https://example.com/kids-first');
