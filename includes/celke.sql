-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 06/04/2018 às 01:36
-- Versão do servidor: 5.7.14
-- Versão do PHP: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `celke`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensagens_contatos`
--

CREATE TABLE `mensagens_contatos` (
  `id` int(11) NOT NULL,
  `nome` varchar(220) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(220) COLLATE utf8_unicode_ci NOT NULL,
  `assunto` varchar(220) COLLATE utf8_unicode_ci NOT NULL,
  `mensagem` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Fazendo dump de dados para tabela `mensagens_contatos`
--

INSERT INTO `mensagens_contatos` (`id`, `nome`, `email`, `assunto`, `mensagem`) VALUES
(1, 'Cesar', 'cesar@celke.com.br', 'ApresentaÃ§Ã£o do Curso', 'ApresentaÃ§Ã£o do Curso'),
(2, 'Cesar', 'cesar@celke.com.br', 'Programas para ComeÃ§ar Aprender PHP', 'Programas para ComeÃ§ar Aprender PHP'),
(3, 'Cesar', 'cesar@celke.com.br', 'Sintaxe BÃ¡sica do PHP', 'Sintaxe BÃ¡sica do PHP'),
(4, 'Cesar', 'cesar@celke.com.br', 'Criar VariÃ¡vel com PHP', 'Criar VariÃ¡vel com PHP'),
(5, 'Cesar', 'cesar@celke.com.br', 'Converter VariÃ¡veis em PHP', 'Converter VariÃ¡veis em PHP'),
(6, 'Cesar', 'cesar@celke.com.br', 'Operadores AritmÃ©ticos', 'Operadores AritmÃ©ticos'),
(7, 'Cesar', 'cesar@celke.com.br', 'Operador de AtribuiÃ§Ã£o', 'Operador de AtribuiÃ§Ã£o'),
(8, 'Cesar', 'cesar@celke.com.br', 'Operador de Incremento e Decremento', 'Operador de Incremento e Decremento'),
(9, 'Cesar', 'cesar@celke.com.br', 'Operadores de ComparaÃ§Ã£o', 'Operadores de ComparaÃ§Ã£o'),
(10, 'Cesar', 'cesar@celke.com.br', 'Operadores LÃ³gicos', 'Operadores LÃ³gicos'),
(11, 'Cesar', 'cesar@celke.com.br', 'Estrutura de Controle IF', 'Estrutura de Controle IF'),
(12, 'Cesar', 'cesar@celke.com.br', 'Estrutura de Controle IF e ELSE', 'Estrutura de Controle IF e ELSE'),
(13, 'Cesar', 'cesar@celke.com.br', 'Estrutura de Controle IF, ELSEIF e ELSE', 'Estrutura de Controle IF, ELSEIF e ELSE'),
(14, 'Cesar', 'cesar@celke.com.br', 'Switch', 'Switch'),
(15, 'Cesar', 'cesar@celke.com.br', 'Comando de RepetiÃ§Ã£o WHILE', 'Comando de RepetiÃ§Ã£o WHILE'),
(16, 'Cesar', 'cesar@celke.com.br', 'Comando de RepetiÃ§Ã£o DO WHILE', 'Comando de RepetiÃ§Ã£o DO WHILE'),
(17, 'Cesar', 'cesar@celke.com.br', 'Comando de RepetiÃ§Ã£o FOR', 'Comando de RepetiÃ§Ã£o FOR'),
(18, 'Cesar', 'cesar@celke.com.br', 'Comando de RepetiÃ§Ã£o FOREACH', 'Comando de RepetiÃ§Ã£o FOREACH'),
(19, 'Cesar', 'cesar@celke.com.br', 'Como Criar FunÃ§Ã£o com PHP', 'Como Criar FunÃ§Ã£o com PHP'),
(20, 'Cesar', 'cesar@celke.com.br', 'FunÃ§Ã£o com Passagem de ParÃ¢metros por Valor e ReferÃªncia', 'FunÃ§Ã£o com Passagem de ParÃ¢metros por Valor e ReferÃªncia'),
(21, 'Cesar', 'cesar@celke.com.br', 'FunÃ§Ã£o Recursiva em PHP', 'FunÃ§Ã£o Recursiva em PHP'),
(22, 'Cesar', 'cesar@celke.com.br', 'FormulÃ¡rio em PHP com MÃ©todo GET', 'FormulÃ¡rio em PHP com MÃ©todo GET'),
(23, 'Cesar', 'cesar@celke.com.br', 'FormulÃ¡rio em PHP com MÃ©todo POST', 'FormulÃ¡rio em PHP com MÃ©todo POST'),
(24, 'Cesar', 'cesar@celke.com.br', 'Cookies', 'Cookies'),
(25, 'Cesar', 'cesar@celke.com.br', 'SessÃ£o', 'SessÃ£o');

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `mensagens_contatos`
--
ALTER TABLE `mensagens_contatos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `mensagens_contatos`
--
ALTER TABLE `mensagens_contatos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
