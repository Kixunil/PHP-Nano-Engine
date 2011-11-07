-- phpMyAdmin SQL Dump
-- version 3.3.7deb5build0.10.10.1
-- http://www.phpmyadmin.net
--
-- Vygenerované:: 06.Nov, 2011 - 21:02
-- Verzia serveru: 5.1.49
-- Verzia PHP: 5.3.3-1ubuntu9.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Štruktúra tabuľky pre tabuľku `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(15) CHARACTER SET utf8 COLLATE utf8_slovak_ci NOT NULL,
  `firstname` text CHARACTER SET utf8 COLLATE utf8_slovak_ci NOT NULL,
  `lastname` text CHARACTER SET utf8 COLLATE utf8_slovak_ci NOT NULL,
  `email` text NOT NULL,
  `password` varchar(80) NOT NULL,
  `cookie` varchar(40) NOT NULL,
  `anticsrf` varchar(40) NOT NULL,
  `money` int(4) unsigned NOT NULL DEFAULT '5',
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `ban` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`,`cookie`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;
