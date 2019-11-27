-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 27, 2019 at 08:02 PM
-- Server version: 5.7.26
-- PHP Version: 7.1.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `club_prepago`
--
CREATE DATABASE IF NOT EXISTS `club_prepago` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `club_prepago`;

-- --------------------------------------------------------

--
-- Table structure for table `account_history`
--

DROP TABLE IF EXISTS `account_history`;
CREATE TABLE IF NOT EXISTS `account_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `payment_id` int(11) DEFAULT NULL,
  `account_type` int(1) NOT NULL COMMENT '1=>balance, 2=>point',
  `amount` float(10,2) NOT NULL,
  `detail` varchar(255) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Account movements';

-- --------------------------------------------------------

--
-- Table structure for table `airtime_purchase_histories`
--

DROP TABLE IF EXISTS `airtime_purchase_histories`;
CREATE TABLE IF NOT EXISTS `airtime_purchase_histories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operator` int(11) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `document_number` varchar(55) NOT NULL COMMENT 'Receipt or Purchase Order',
  `purchase_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Purchase Date',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

DROP TABLE IF EXISTS `banks`;
CREATE TABLE IF NOT EXISTS `banks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bank_name` varchar(55) NOT NULL COMMENT 'Bank name',
  `account_number` varchar(55) NOT NULL COMMENT 'Account Number',
  `account_type` int(1) NOT NULL COMMENT '1 => Corriente, 2 => Ahorro',
  `delete_status` int(1) NOT NULL COMMENT '0 => Active, 1 => Deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(55) NOT NULL,
  `tax` float(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `coupon_type` int(4) NOT NULL,
  `description` varchar(500) NOT NULL,
  `amount` float(10,2) NOT NULL,
  `cant` int(8) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `due_date` datetime NOT NULL,
  `status` int(11) NOT NULL,
  `image` varchar(100) NOT NULL,
  `points` int(50) NOT NULL,
  `delete_status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_redemptions`
--

DROP TABLE IF EXISTS `coupon_redemptions`;
CREATE TABLE IF NOT EXISTS `coupon_redemptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coupon_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `reference_no` varchar(50) DEFAULT NULL,
  `purchase_date` datetime DEFAULT NULL,
  `redeem_date` datetime DEFAULT NULL,
  `x` varchar(100) DEFAULT NULL,
  `y` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `creditcard_transactions`
--

DROP TABLE IF EXISTS `creditcard_transactions`;
CREATE TABLE IF NOT EXISTS `creditcard_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'User that sent the payment',
  `transaction_id` varchar(55) NOT NULL COMMENT 'Payment processor transaction ID',
  `amount` float(10,2) NOT NULL COMMENT 'Payment amount',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '0 => Pending, 1 => Accepted, 2 => Denied',
  `transaction_date` datetime NOT NULL COMMENT 'Date & Time of transaction',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Modified',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Credit card transactions';

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

DROP TABLE IF EXISTS `devices`;
CREATE TABLE IF NOT EXISTS `devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'User ID',
  `device_id` int(11) NOT NULL COMMENT 'Device ID',
  `device_token` varchar(100) DEFAULT NULL COMMENT 'Device Token',
  `platform_id` int(11) NOT NULL COMMENT 'Platform ID',
  `login_status` int(1) NOT NULL COMMENT '0=>logged out, 1=>logged in',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Modified',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

DROP TABLE IF EXISTS `favorites`;
CREATE TABLE IF NOT EXISTS `favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'User this Favorite is assigned to',
  `name` varchar(100) NOT NULL COMMENT 'Favorite Name',
  `phone_number` varchar(15) NOT NULL COMMENT 'Favorite Phone Number',
  `operator` int(11) NOT NULL COMMENT '1 => Movistar, 2 => Digitel',
  `delete_status` int(1) NOT NULL DEFAULT '0' COMMENT '0 => Active, 1 => Deleted',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Users'' favorite numbers';

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
CREATE TABLE IF NOT EXISTS `invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_id` int(11) NOT NULL COMMENT 'If the invoice corresponds to a Payment',
  `documento` varchar(20) DEFAULT NULL COMMENT 'Invoice document number',
  `nombre` varchar(80) NOT NULL COMMENT 'Client''s Name',
  `ruc` varchar(15) NOT NULL COMMENT 'Client''s Cedula or RIF',
  `direccion` varchar(150) NOT NULL COMMENT 'Client''s Address',
  `descuento` float(19,3) NOT NULL DEFAULT '0.000' COMMENT 'Discount amount',
  `total_pagos` float(19,2) NOT NULL DEFAULT '0.00' COMMENT 'Total payment',
  `total_final` float(19,2) NOT NULL DEFAULT '0.00' COMMENT 'Total amount',
  `recargos` float(19,2) NOT NULL DEFAULT '0.00' COMMENT 'Service charge',
  `porcentaje_recargo` float(7,2) NOT NULL DEFAULT '0.00' COMMENT 'Service charge percentage',
  `efectivo` float(19,2) NOT NULL DEFAULT '0.00' COMMENT 'Cash payment',
  `cheque` float(19,2) NOT NULL DEFAULT '0.00' COMMENT 'Check payment',
  `tarjeta_credito` float(19,2) NOT NULL DEFAULT '0.00' COMMENT 'Credit card payment',
  `tarjeta_debito` float(19,2) NOT NULL DEFAULT '0.00' COMMENT 'Debit card payment',
  `nota_credito` float(19,2) NOT NULL DEFAULT '0.00' COMMENT 'Credit note',
  `codigo` varchar(25) NOT NULL COMMENT 'Product code',
  `nombre_articulo` varchar(25) NOT NULL COMMENT 'Product name',
  `unidad` varchar(20) NOT NULL COMMENT 'Sales unit',
  `cantidad` float(19,2) NOT NULL COMMENT 'Product amount',
  `precio_neto_unit` float(19,2) NOT NULL COMMENT 'Net unit price',
  `alicuota` float(10,2) NOT NULL COMMENT 'Tax rate',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Modified',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `operators`
--

DROP TABLE IF EXISTS `operators`;
CREATE TABLE IF NOT EXISTS `operators` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT 'Mobile operator''s name',
  `productId` varchar(10) NOT NULL COMMENT 'TrxEngine ProductId',
  `country_id` int(3) NOT NULL COMMENT 'Country ID',
  `balance` float(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Available balance',
  `minimum_limit` float(10,2) NOT NULL COMMENT 'Warning level',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0 => Inactive, 1 => Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `operators`
--

INSERT INTO `operators` (`id`, `name`, `productId`, `country_id`, `balance`, `minimum_limit`, `status`) VALUES
(1, 'Movistar', '01', 1, 0.00, 10000.00, 1),
(2, 'Digitel', '02', 1, 0.00, 10000.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `operator_credentials`
--

DROP TABLE IF EXISTS `operator_credentials`;
CREATE TABLE IF NOT EXISTS `operator_credentials` (
  `operator_id` int(11) NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  `port` varchar(5) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `product_id` varchar(2) DEFAULT NULL,
  `token` varchar(500) DEFAULT NULL,
  UNIQUE KEY `operator_id` (`operator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `operator_credentials`
--

INSERT INTO `operator_credentials` (`operator_id`, `ip_address`, `port`, `username`, `password`, `product_id`, `token`) VALUES
(1, 'https://api-ve.movilway.net:5805/Service/ExtendedApi/Public/ExtendedApi.svc?singlewsdl', '', '00000000', '00000000', '5', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'User that sent the payment notification',
  `user_type` int(1) NOT NULL COMMENT '1 => User, 2 => Reseller',
  `payment_method` int(1) NOT NULL COMMENT '1 => Deposit, 2 => Credit Card',
  `bank_id` int(11) NOT NULL DEFAULT '99' COMMENT 'Bank ID',
  `reference_number` varchar(100) NOT NULL COMMENT 'Reference number',
  `amount` float(10,2) NOT NULL COMMENT 'Payment amount',
  `tax` float(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Tax withheld',
  `fees` float(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Credit Card Fees',
  `net_amount` float(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Net payment amount',
  `discount` float(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Reseller''s discount credit',
  `amount_credited` float(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Amount added to balance',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '0 => Pending, 1 => Approved, 2 => Denied',
  `promo_number` varchar(50) DEFAULT NULL,
  `denial_reason` varchar(255) NOT NULL DEFAULT '' COMMENT 'Reason for denial',
  `notification_date` datetime NOT NULL COMMENT 'Date & Time of notification',
  `change_status_date` datetime DEFAULT NULL COMMENT 'Latest update',
  `x` varchar(100) DEFAULT NULL COMMENT 'Longitude',
  `y` varchar(100) DEFAULT NULL COMMENT 'Latitude',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Modified',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='User and Reseller payment notifications';

-- --------------------------------------------------------

--
-- Table structure for table `platforms`
--

DROP TABLE IF EXISTS `platforms`;
CREATE TABLE IF NOT EXISTS `platforms` (
  `id` int(4) NOT NULL AUTO_INCREMENT COMMENT 'Platform ID',
  `device` varchar(50) NOT NULL COMMENT 'Device type',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `platforms`
--

INSERT INTO `platforms` (`id`, `device`) VALUES
(1, 'Club Prepago Celular');

-- --------------------------------------------------------

--
-- Table structure for table `recharges`
--

DROP TABLE IF EXISTS `recharges`;
CREATE TABLE IF NOT EXISTS `recharges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'User ID',
  `user_type` int(1) NOT NULL DEFAULT '1' COMMENT '1 => User, 2 => Reseller',
  `phone_number` varchar(15) NOT NULL COMMENT 'Destination phone number',
  `operator` int(4) NOT NULL COMMENT 'Mobile operator',
  `amount` float(10,2) NOT NULL COMMENT 'Recharge amount',
  `tax_amount` float(10,2) NOT NULL COMMENT 'Tax amount',
  `total_amount` float(10,2) NOT NULL COMMENT 'Total amount',
  `recharge_date` datetime NOT NULL COMMENT 'Recharge date & time',
  `payment_method` int(1) NOT NULL COMMENT '1 => Balance,  2 => Credit Card, 3 => Points',
  `status` int(1) DEFAULT NULL COMMENT '0 => Failed, 1 => Successful, 2 => Fixed',
  `promo_number` varchar(50) DEFAULT NULL COMMENT 'Randomly generated value for promotions',
  `merchant_txn_id` varchar(255) DEFAULT NULL COMMENT 'MerchantTxnId',
  `replaced_by` varchar(255) DEFAULT NULL,
  `payment_id` varchar(255) DEFAULT NULL,
  `points` int(50) NOT NULL DEFAULT '0' COMMENT 'Points earned for this recharge',
  `response_code` varchar(50) DEFAULT NULL COMMENT 'Mobile Operator''s response code',
  `response_message` varchar(255) DEFAULT NULL COMMENT 'Mobile operator''s response message',
  `y` varchar(100) DEFAULT NULL COMMENT 'Latitude',
  `x` varchar(100) DEFAULT NULL COMMENT 'Longitude',
  `timestap` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Modified',
  PRIMARY KEY (`id`),
  KEY `idx_recharges_merchant_txn_id` (`merchant_txn_id`) COMMENT='Index by transacction id'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rechargeve`
--

DROP TABLE IF EXISTS `rechargeve`;
CREATE TABLE IF NOT EXISTS `rechargeve` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recharge_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `phone_number` varchar(11) NOT NULL,
  `operator` int(11) NOT NULL,
  `amount` float(10,2) NOT NULL,
  `points` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `trxid` varchar(25) DEFAULT NULL,
  `notification_date` datetime NOT NULL,
  `change_status_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `redemptions`
--

DROP TABLE IF EXISTS `redemptions`;
CREATE TABLE IF NOT EXISTS `redemptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `points` int(10) NOT NULL COMMENT 'Points spent on this reward',
  `redeem_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reward_type` int(4) NOT NULL COMMENT '1=>Recharge, 2=>Customer Support,3=>Download',
  `reward_id` int(11) DEFAULT NULL,
  `phone_number` varchar(50) NOT NULL,
  `operator` varchar(3) NOT NULL,
  `redemption_code` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rejection_reasons`
--

DROP TABLE IF EXISTS `rejection_reasons`;
CREATE TABLE IF NOT EXISTS `rejection_reasons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(500) DEFAULT NULL,
  `delete_status` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rejection_reasons`
--

INSERT INTO `rejection_reasons` (`id`, `description`, `delete_status`, `created`) VALUES
(1, 'Ha culminado el tiempo de espera y no hemos recibido el pago por esta compra. Cualquier duda o consulta, estamos a la orden por info@clubprepago.com', 0, '2017-08-15 12:35:57');

-- --------------------------------------------------------

--
-- Table structure for table `rewards`
--

DROP TABLE IF EXISTS `rewards`;
CREATE TABLE IF NOT EXISTS `rewards` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Reward ID',
  `reward_type` int(4) NOT NULL DEFAULT '1' COMMENT '1 => Recharge',
  `points` int(11) NOT NULL COMMENT 'Reward cost in points',
  `value` float(10,2) NOT NULL COMMENT 'Reward value in USD',
  `description` varchar(255) NOT NULL COMMENT 'Reward description',
  `image` varchar(255) NOT NULL COMMENT 'Reward image',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '0 => Inactive, 1 => Active',
  `delete_status` int(1) NOT NULL DEFAULT '0' COMMENT '0 => Not Deleted, 1 => Deleted',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Modified',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reward_points`
--

DROP TABLE IF EXISTS `reward_points`;
CREATE TABLE IF NOT EXISTS `reward_points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `point` int(50) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(2) NOT NULL COMMENT '1=>credited, 2=>debited',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reward_signup` int(10) NOT NULL COMMENT 'Points granted for signing up',
  `reward_referral` int(10) NOT NULL COMMENT 'Points for referring another user',
  `reward_recharge` int(10) NOT NULL COMMENT 'Points per dollar recharged',
  `reward_social` int(10) NOT NULL COMMENT 'Points for interaction with social networks',
  `discount_rate` float(5,2) NOT NULL COMMENT 'Default reseller discount rate',
  `fee_ve` float(5,2) NOT NULL DEFAULT '10.00',
  `credit_card_fee_percent` float(5,2) NOT NULL COMMENT 'Credit Card Fee (%)',
  `credit_card_fee_fixed` float(10,2) NOT NULL COMMENT 'Credit Card Fee (Fixed)',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Modified',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `reward_signup`, `reward_referral`, `reward_recharge`, `reward_social`, `discount_rate`, `fee_ve`, `credit_card_fee_percent`, `credit_card_fee_fixed`, `timestamp`) VALUES
(1, 100, 10, 10, 100, 7.00, 15.00, 0.00, 0.00, '2018-03-14 17:08:55');

-- --------------------------------------------------------

--
-- Table structure for table `sponsors`
--

DROP TABLE IF EXISTS `sponsors`;
CREATE TABLE IF NOT EXISTS `sponsors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tax_id` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `phone_number` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Phone number',
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country_id` int(11) NOT NULL,
  `registered` datetime NOT NULL COMMENT 'Registration date',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0=>Inactive, 1 =>Active',
  `delete_status` int(1) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Modified',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sponsors`
--

INSERT INTO `sponsors` (`id`, `name`, `email`, `tax_id`, `phone_number`, `address`, `city`, `state`, `country_id`, `registered`, `status`, `delete_status`, `timestamp`) VALUES
(1, 'Club Prepago', 'info@clubprepago.com', '1245539-1-592069 DV 04', '3886220', 'Ave. Israel, PH Ramada Plaza Panama, Oficina M2-6', 'Ciudad de Panama', 'Panama', 1, '2015-12-17 15:19:20', 1, 0, '2016-07-12 14:36:30');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
CREATE TABLE IF NOT EXISTS `staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT 'Name',
  `email` varchar(100) NOT NULL COMMENT 'Email address',
  `username` varchar(100) NOT NULL COMMENT 'Username',
  `password` varchar(100) NOT NULL COMMENT 'Password',
  `type` int(1) NOT NULL COMMENT '1 => Support, 2 => Supervisor, 3 => Manager, 4 => Recharger, 5 => Verifier',
  `generate_recharge_access` int(1) NOT NULL DEFAULT '1' COMMENT '0 => Can''t recharge, 1 => Can recharge',
  `language` varchar(3) NOT NULL DEFAULT '2',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Modified',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='Club Prepago Staff Members';

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `name`, `email`, `username`, `password`, `type`, `generate_recharge_access`, `language`, `timestamp`) VALUES
(1, 'Admin', 'admin@clubprepago.com', 'admin', 'bf97f929c8b9e4eb8fcc39df6d165169ade3604c', 3, 1, '1', '2019-11-27 19:22:38');

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

DROP TABLE IF EXISTS `stores`;
CREATE TABLE IF NOT EXISTS `stores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(500) NOT NULL,
  `description` varchar(200) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone_numer` varchar(20) NOT NULL,
  `country_id` int(11) NOT NULL,
  `created_date` date NOT NULL,
  `delete_status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'User ID',
  `user_type` int(1) NOT NULL COMMENT '1 => User, 2 => Reseller',
  `name` varchar(100) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Name',
  `tax_id` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Cedula or Passport',
  `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Home address',
  `city` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'City or Corregimiento',
  `state` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Province',
  `country_id` int(1) NOT NULL DEFAULT '1' COMMENT 'Country ID',
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Email address',
  `password` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Password',
  `sponsor_id` int(11) DEFAULT '0' COMMENT 'Sponsor ID',
  `discount_rate` float(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Reseller discount percentage',
  `phone_number` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Phone number',
  `balance` float(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Available balance',
  `points` float(10,0) NOT NULL DEFAULT '0' COMMENT 'Available reward points',
  `email_verify` int(1) NOT NULL DEFAULT '0' COMMENT '0 => Not Verified, 1 => Verified',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '0 => Inactive, 1 => Active, 2 => Banned',
  `banned` int(1) NOT NULL DEFAULT '0' COMMENT '0 => Not Banned, 1 => Banned',
  `delete_status` int(1) NOT NULL DEFAULT '0' COMMENT '0 => Active, 1 => Deleted',
  `registered` timestamp NULL DEFAULT NULL COMMENT 'Registration date',
  `image` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `x` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Longitude',
  `y` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Latitude',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Modified',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_stores`
--

DROP TABLE IF EXISTS `users_stores`;
CREATE TABLE IF NOT EXISTS `users_stores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rol_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `versions`
--

DROP TABLE IF EXISTS `versions`;
CREATE TABLE IF NOT EXISTS `versions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'App name',
  `version` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Latest App Version',
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Latest update',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `versions`
--

INSERT INTO `versions` (`id`, `app_name`, `version`, `datetime`) VALUES
(1, 'Club Prepago Celular', '1.1', '2017-12-17 21:16:05');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
