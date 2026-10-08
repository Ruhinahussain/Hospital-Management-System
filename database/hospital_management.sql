-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 03, 2026 at 02:10 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hospital_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `Appointment_ID` int(11) NOT NULL,
  `Patient_ID` int(11) NOT NULL,
  `Doctor_ID` int(11) NOT NULL,
  `Appointment_Date` date NOT NULL,
  `Appointment_Time` time NOT NULL,
  `Reason` varchar(255) DEFAULT NULL,
  `Status` varchar(30) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`Appointment_ID`, `Patient_ID`, `Doctor_ID`, `Appointment_Date`, `Appointment_Time`, `Reason`, `Status`) VALUES
(1, 1, 1, '2026-10-05', '10:00:00', 'Chest discomfort', 'Confirmed'),
(2, 2, 2, '2026-10-06', '11:30:00', 'Headache', 'Pending'),
(3, 3, 3, '2026-10-07', '14:00:00', 'Knee pain', 'Confirmed'),
(4, 1, 1, '2026-10-07', '16:00:00', 'geneal checkup', 'Pending'),
(5, 1, 1, '2026-10-03', '16:25:00', 'leg pain', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `bill`
--

CREATE TABLE `bill` (
  `Bill_ID` int(11) NOT NULL,
  `Appointment_ID` int(11) NOT NULL,
  `Amount` decimal(10,2) NOT NULL,
  `Bill_Date` date DEFAULT NULL,
  `Payment_Status` varchar(30) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bill`
--

INSERT INTO `bill` (`Bill_ID`, `Appointment_ID`, `Amount`, `Bill_Date`, `Payment_Status`) VALUES
(1, 1, 800.00, '2026-10-05', 'Pending'),
(2, 2, 500.00, '2026-10-06', 'Pending'),
(3, 3, 650.00, '2026-10-07', 'Paid'),
(4, 4, 700.00, '2026-10-03', 'Pending'),
(5, 5, 2300.00, '2026-10-03', 'Paid');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `Department_ID` int(11) NOT NULL,
  `Department_Name` varchar(100) NOT NULL,
  `Location` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`Department_ID`, `Department_Name`, `Location`) VALUES
(1, 'Cardiology', 'Block A'),
(2, 'Neurology', 'Block B'),
(3, 'Orthopedics', 'Block C');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `Doctor_ID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Specialization` varchar(100) DEFAULT NULL,
  `Phone` varchar(15) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Department_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`Doctor_ID`, `Name`, `Specialization`, `Phone`, `Email`, `Department_ID`) VALUES
(1, 'Dr. Ananya Rao', 'Cardiology', '9876500001', 'ananya@hospital.com', 1),
(2, 'Dr. Rahul Sharma', 'Neurology', '9876500002', 'rahul@hospital.com', 2),
(3, 'Dr. Meera Patel', 'Orthopedics', '9876500003', 'meera@hospital.com', 3),
(4, 'fish', 'cardiology', '2053698754', 'fish@gmail.com', 1);

-- --------------------------------------------------------

--
-- Table structure for table `medicine`
--

CREATE TABLE `medicine` (
  `Medicine_ID` int(11) NOT NULL,
  `Medicine_Name` varchar(100) NOT NULL,
  `Price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `Stock` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicine`
--

INSERT INTO `medicine` (`Medicine_ID`, `Medicine_Name`, `Price`, `Stock`) VALUES
(1, 'Paracetamol', 20.00, 100),
(2, 'Vitamin D', 50.00, 80),
(3, 'Antacid', 35.00, 60),
(4, 'Paracetamol', 5.00, 10),
(5, 'dolo 650', 3.00, 12),
(6, 'TestMedicineNegative', 50.00, 10);

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `Patient_ID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `DOB` date DEFAULT NULL,
  `Gender` varchar(20) DEFAULT NULL,
  `Phone` varchar(15) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`Patient_ID`, `Name`, `DOB`, `Gender`, `Phone`, `Address`, `Email`) VALUES
(1, 'Aarav Kumar', '1998-05-12', 'Male', '9876500011', 'Bengaluru', 'aarav@example.com'),
(2, 'Diya Sharma', '2002-08-21', 'Female', '9876500012', 'Tumakuru', 'diya@example.com'),
(3, 'Arjun Rao', '1985-11-03', 'Male', '9876500013', 'Mysuru', 'arjun@example.com'),
(4, 'xyz', '2013-06-04', 'Male', '9000000000', 'abcd colony', 'xyz@gamil.com');

-- --------------------------------------------------------

--
-- Table structure for table `prescription`
--

CREATE TABLE `prescription` (
  `Prescription_ID` int(11) NOT NULL,
  `Appointment_ID` int(11) NOT NULL,
  `Diagnosis` varchar(255) DEFAULT NULL,
  `Prescription_Date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescription`
--

INSERT INTO `prescription` (`Prescription_ID`, `Appointment_ID`, `Diagnosis`, `Prescription_Date`) VALUES
(1, 1, 'Needs cardiac evaluation', '2026-10-05'),
(2, 2, 'Needs neurological evaluation', '2026-10-06'),
(3, 4, 'General checkup. Follow the doctor\'s advice and return for a follow-up if required.', '2026-10-07'),
(4, 5, 'need scanning', '2026-10-03'),
(6, 3, 'need scanning', '2026-10-10');

-- --------------------------------------------------------

--
-- Table structure for table `prescription_medicine`
--

CREATE TABLE `prescription_medicine` (
  `Prescription_ID` int(11) NOT NULL,
  `Medicine_ID` int(11) NOT NULL,
  `Dosage` varchar(100) DEFAULT NULL,
  `Duration` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescription_medicine`
--

INSERT INTO `prescription_medicine` (`Prescription_ID`, `Medicine_ID`, `Dosage`, `Duration`) VALUES
(1, 1, 'As directed by doctor', '3 days'),
(1, 2, 'As directed by doctor', '7 days'),
(2, 3, 'As directed by doctor', '5 days');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`Appointment_ID`),
  ADD KEY `Patient_ID` (`Patient_ID`),
  ADD KEY `Doctor_ID` (`Doctor_ID`);

--
-- Indexes for table `bill`
--
ALTER TABLE `bill`
  ADD PRIMARY KEY (`Bill_ID`),
  ADD UNIQUE KEY `Appointment_ID` (`Appointment_ID`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`Department_ID`),
  ADD UNIQUE KEY `Department_Name` (`Department_Name`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`Doctor_ID`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `Department_ID` (`Department_ID`);

--
-- Indexes for table `medicine`
--
ALTER TABLE `medicine`
  ADD PRIMARY KEY (`Medicine_ID`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`Patient_ID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `prescription`
--
ALTER TABLE `prescription`
  ADD PRIMARY KEY (`Prescription_ID`),
  ADD UNIQUE KEY `Appointment_ID` (`Appointment_ID`);

--
-- Indexes for table `prescription_medicine`
--
ALTER TABLE `prescription_medicine`
  ADD PRIMARY KEY (`Prescription_ID`,`Medicine_ID`),
  ADD KEY `Medicine_ID` (`Medicine_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `Appointment_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bill`
--
ALTER TABLE `bill`
  MODIFY `Bill_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `Department_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `Doctor_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `medicine`
--
ALTER TABLE `medicine`
  MODIFY `Medicine_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `Patient_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `prescription`
--
ALTER TABLE `prescription`
  MODIFY `Prescription_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`Patient_ID`) REFERENCES `patient` (`Patient_ID`),
  ADD CONSTRAINT `appointment_ibfk_2` FOREIGN KEY (`Doctor_ID`) REFERENCES `doctor` (`Doctor_ID`);

--
-- Constraints for table `bill`
--
ALTER TABLE `bill`
  ADD CONSTRAINT `bill_ibfk_1` FOREIGN KEY (`Appointment_ID`) REFERENCES `appointment` (`Appointment_ID`);

--
-- Constraints for table `doctor`
--
ALTER TABLE `doctor`
  ADD CONSTRAINT `doctor_ibfk_1` FOREIGN KEY (`Department_ID`) REFERENCES `department` (`Department_ID`);

--
-- Constraints for table `prescription`
--
ALTER TABLE `prescription`
  ADD CONSTRAINT `prescription_ibfk_1` FOREIGN KEY (`Appointment_ID`) REFERENCES `appointment` (`Appointment_ID`);

--
-- Constraints for table `prescription_medicine`
--
ALTER TABLE `prescription_medicine`
  ADD CONSTRAINT `prescription_medicine_ibfk_1` FOREIGN KEY (`Prescription_ID`) REFERENCES `prescription` (`Prescription_ID`),
  ADD CONSTRAINT `prescription_medicine_ibfk_2` FOREIGN KEY (`Medicine_ID`) REFERENCES `medicine` (`Medicine_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
