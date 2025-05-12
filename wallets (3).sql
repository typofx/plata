-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 19/11/2024 às 22:02
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `datawallet`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `wallets`
--

CREATE TABLE `wallets` (
  `WALLET_SECRET` varchar(255) NOT NULL,
  `WALLET_ADDRESS` varchar(255) NOT NULL,
  `ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `wallets`
--

INSERT INTO `wallets` (`WALLET_SECRET`, `WALLET_ADDRESS`, `ID`) VALUES
('8425f80608e14a7b8fdcc59172fb9b38a824accf163f662f8a6032fc93ad04c8', '0xAA5E5a7f9B2E300e0cDef96C6d9c5810072A62Da', 7),
('5a2e4c1468eeaf7986ecfdd7049c4667391c369761c13e6f8abf022fe4958d2e', '0x3D00Da18f52dB265fCAB7769df6de171DeF829CE', 8),
('09e411fc4f8ef98e01e1d933ae44da2b277f532315f288194add27da7733ecb2', '0xD611F9A331AE7084D65620d0AA14931c87C95894', 9),
('fa7012e27e8cf6ee74ae026b0d384522080f38f016ef06c79269d6a779c0af53', '0x02ae4e50F94B6913b4a6F3f75875BEFb8608582D', 10),
('5e7fe93d60c60e8d6a9e34963dc67b95b9ff2add5db174c3b037d8934d790dd2', '0x0D073F088E4a3395e079Cc415D3EeDF105A6A09f', 11);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `wallets`
--
ALTER TABLE `wallets`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
