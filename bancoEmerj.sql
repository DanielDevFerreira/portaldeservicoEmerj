-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 27-Jul-2021 às 13:10
-- Versão do servidor: 10.4.20-MariaDB
-- versão do PHP: 8.0.8

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `db_emerj`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_atendimento`
--

DROP TABLE IF EXISTS `tb_atendimento`;
CREATE TABLE IF NOT EXISTS `tb_atendimento` (
  `atendimento_id` int(11) NOT NULL AUTO_INCREMENT,
  `id_servico` int(11) NOT NULL,
  `id_itemdeconfiguracao` int(11) NOT NULL,
  `data_add` datetime DEFAULT current_timestamp(),
  `data_up` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`atendimento_id`),
  UNIQUE KEY `uk_atendimento` (`id_servico`,`id_itemdeconfiguracao`),
  KEY `fk_itemdeconfiguracao` (`id_itemdeconfiguracao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncar tabela antes do insert `tb_atendimento`
--

TRUNCATE TABLE `tb_atendimento`;
-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_cargo`
--

DROP TABLE IF EXISTS `tb_cargo`;
CREATE TABLE IF NOT EXISTS `tb_cargo` (
  `cargo_id` int(11) NOT NULL AUTO_INCREMENT,
  `cargo_nm` varchar(70) NOT NULL,
  `cargo_des` varchar(255) NOT NULL,
  `data_add` datetime DEFAULT current_timestamp(),
  `data_up` datetime DEFAULT current_timestamp(),
  `ativo_fl` enum('s','n') DEFAULT 's',
  PRIMARY KEY (`cargo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Truncar tabela antes do insert `tb_cargo`
--

TRUNCATE TABLE `tb_cargo`;
--
-- Extraindo dados da tabela `tb_cargo`
--

INSERT INTO `tb_cargo` (`cargo_id`, `cargo_nm`, `cargo_des`, `data_add`, `data_up`, `ativo_fl`) VALUES
(1, 'Analista de Sistemas', 'Analista de Sistemas', '2021-07-21 17:38:58', '2021-07-21 17:38:58', 's'),
(2, 'Diretor de Divisão', 'Diretor de Divisão', '2021-07-24 23:42:29', '2021-07-24 23:42:29', 's'),
(3, 'Pesquisador', 'Pesquisador', '2021-07-27 06:49:32', '2021-07-27 06:49:32', 's'),
(4, 'Estagiário', 'Estagiário', '2021-07-27 06:49:32', '2021-07-27 06:49:32', 's'),
(5, 'Professor', 'Professor', '2021-07-27 06:49:32', '2021-07-27 06:49:32', 's'),
(6, 'Prestador de Serviço', 'Prestador de Serviço', '2021-07-27 06:49:32', '2021-07-27 06:49:32', 's'),
(7, 'Servidor', 'Servidor', '2021-07-27 06:49:32', '2021-07-27 06:49:32', 's');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_chamado`
--

DROP TABLE IF EXISTS `tb_chamado`;
CREATE TABLE IF NOT EXISTS `tb_chamado` (
  `chamado_id` int(11) NOT NULL AUTO_INCREMENT,
  `chamado_nm` varchar(60) NOT NULL,
  `chamado_des` varchar(255) NOT NULL,
  `data_add` datetime DEFAULT current_timestamp(),
  `data_up` datetime DEFAULT current_timestamp(),
  `ativo_fl` enum('s','n') DEFAULT 's',
  `id_usuario` int(11) NOT NULL,
  `autorizado_por` int(11) DEFAULT NULL,
  `atendido_por` int(11) DEFAULT NULL,
  `solicitado_por` int(11) DEFAULT NULL,
  `dt_atendimento` datetime DEFAULT current_timestamp(),
  `id_status` int(11) NOT NULL,
  `id_atendimento` int(11) NOT NULL,
  PRIMARY KEY (`chamado_id`),
  KEY `fk_usuario` (`id_usuario`),
  KEY `fk_status` (`id_status`),
  KEY `fk_atendimento` (`id_atendimento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncar tabela antes do insert `tb_chamado`
--

TRUNCATE TABLE `tb_chamado`;
-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_departamento`
--

DROP TABLE IF EXISTS `tb_departamento`;
CREATE TABLE IF NOT EXISTS `tb_departamento` (
  `departamento_id` int(11) NOT NULL AUTO_INCREMENT,
  `departamento_nm` varchar(100) NOT NULL,
  `departamento_des` varchar(255) NOT NULL,
  `departamento_sg` varchar(10) NOT NULL,
  `data_add` datetime DEFAULT current_timestamp(),
  `data_up` datetime DEFAULT current_timestamp(),
  `ativo_fl` enum('s','n') DEFAULT 's',
  `cod_dep_super` int(11) NOT NULL,
  PRIMARY KEY (`departamento_id`),
  KEY `cod_dep_super` (`cod_dep_super`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Truncar tabela antes do insert `tb_departamento`
--

TRUNCATE TABLE `tb_departamento`;
--
-- Extraindo dados da tabela `tb_departamento`
--

INSERT INTO `tb_departamento` (`departamento_id`, `departamento_nm`, `departamento_des`, `departamento_sg`, `data_add`, `data_up`, `ativo_fl`, `cod_dep_super`) VALUES
(1, 'Tribunal de Justiça do Estado do Rio de Janeiro', 'Tribunal de Justiça do Estado do Rio de Janeiro', 'TJRJ', '2021-07-18 23:45:08', '2021-07-18 23:45:08', 's', 1),
(4, 'Escola de Magistratura do Tribunal de Justiça do Estado do Rio de Janeiro', 'Escola de Magistratura do Tribunal de Justiça do Estado do Rio de Janeiro', 'EMERJ', '2021-07-19 02:57:25', '2021-07-19 02:57:25', NULL, 1),
(5, 'Diretoria de Tecnologia', 'Diretoria de Tecnologia', 'DETEC', '2021-07-19 04:35:49', '2021-07-19 04:35:49', NULL, 4),
(6, 'Diretoria de Comunicação', 'Diretoria de Comunicação', 'DECOM', '2021-07-19 04:36:40', '2021-07-19 04:36:40', NULL, 4);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_itemdeconfiguracao`
--

DROP TABLE IF EXISTS `tb_itemdeconfiguracao`;
CREATE TABLE IF NOT EXISTS `tb_itemdeconfiguracao` (
  `itemdeconfiguracao_id` int(11) NOT NULL AUTO_INCREMENT,
  `itemdeconfiguracao_nm` varchar(60) NOT NULL,
  `itemdeconfiguracao_des` varchar(255) NOT NULL,
  `data_add` datetime DEFAULT current_timestamp(),
  `data_up` datetime DEFAULT current_timestamp(),
  `ativo_fl` enum('s','n') DEFAULT 's',
  `id_tipodeic` int(11) NOT NULL,
  PRIMARY KEY (`itemdeconfiguracao_id`),
  KEY `fk_tipodeic` (`id_tipodeic`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncar tabela antes do insert `tb_itemdeconfiguracao`
--

TRUNCATE TABLE `tb_itemdeconfiguracao`;
-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_perfil`
--

DROP TABLE IF EXISTS `tb_perfil`;
CREATE TABLE IF NOT EXISTS `tb_perfil` (
  `perfil_id` int(11) NOT NULL AUTO_INCREMENT,
  `perfil_nm` varchar(20) NOT NULL,
  `perfil_des` varchar(255) NOT NULL,
  `data_add` datetime DEFAULT current_timestamp(),
  `data_up` datetime DEFAULT current_timestamp(),
  `ativo_fl` enum('s','n') DEFAULT 's',
  PRIMARY KEY (`perfil_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncar tabela antes do insert `tb_perfil`
--

TRUNCATE TABLE `tb_perfil`;
--
-- Extraindo dados da tabela `tb_perfil`
--

INSERT INTO `tb_perfil` (`perfil_id`, `perfil_nm`, `perfil_des`, `data_add`, `data_up`, `ativo_fl`) VALUES
(1, 'DESENVOLVEDOR', 'Consulta, cadastra, altera e exclui qualquer registro.', '2021-07-21 17:38:59', '2021-07-21 17:38:59', 's'),
(2, 'SUPER_USUARIO', 'Consulta, cadastra e altera qualquer informação não sensível ao sistema.', '2021-07-21 17:38:59', '2021-07-21 17:38:59', 's'),
(3, 'GESTOR', 'Consulta, cadastra e altera informações do seu departamento e altera informações não sensíveis externas ao seu departamento.', '2021-07-21 17:38:59', '2021-07-21 17:38:59', 's'),
(4, 'OPERADOR', 'Consulta e cadastra informações do seu departamento e altera informações não sensíveis do seu departamento.', '2021-07-21 17:38:59', '2021-07-21 17:38:59', 's'),
(5, 'BASICO', 'Consulta apenas informações do seu departamento.', '2021-07-21 17:39:00', '2021-07-21 17:39:00', 's'),
(6, 'ALUNO', 'Consulta apenas informações de alunos ', '2021-07-26 21:11:03', '2021-07-26 21:11:03', 's'),
(7, 'PROFESSOR', 'Consulta apenas informações de professores', '2021-07-26 21:11:47', '2021-07-26 21:11:47', 's'),
(8, 'FORNECEDOR', 'Consulta apenas informações de fornecedores', '2021-07-26 21:13:07', '2021-07-26 21:13:07', 's');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_servico`
--

DROP TABLE IF EXISTS `tb_servico`;
CREATE TABLE IF NOT EXISTS `tb_servico` (
  `servico_id` int(11) NOT NULL AUTO_INCREMENT,
  `servico_nm` varchar(60) NOT NULL,
  `servico_des` varchar(255) NOT NULL,
  `data_add` datetime DEFAULT current_timestamp(),
  `data_up` datetime DEFAULT current_timestamp(),
  `ativo_fl` enum('s','n') DEFAULT 's',
  `id_departamento` int(11) NOT NULL,
  `id_tipodeservico` int(11) NOT NULL,
  PRIMARY KEY (`servico_id`),
  KEY `fk_departamento` (`id_departamento`),
  KEY `fk_tipodeservico` (`id_tipodeservico`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncar tabela antes do insert `tb_servico`
--

TRUNCATE TABLE `tb_servico`;
--
-- Extraindo dados da tabela `tb_servico`
--

INSERT INTO `tb_servico` (`servico_id`, `servico_nm`, `servico_des`, `data_add`, `data_up`, `ativo_fl`, `id_departamento`, `id_tipodeservico`) VALUES
(1, 'Conceder acesso', 'Criação de login  ', '2021-07-27 06:34:21', '2021-07-27 06:34:21', 's', 5, 3),
(2, 'Acesso ao grupo de e-mail', 'Acesso ao grupo de e-mail', '2021-07-27 06:34:21', '2021-07-27 06:34:21', 's', 5, 3),
(3, 'Reinicializar senha', 'Solicitação de reinicialização de senha', '2021-07-27 06:34:22', '2021-07-27 06:34:22', 's', 5, 3),
(4, 'Cancelar acesso', 'Cancelar acesso', '2021-07-27 06:47:36', '2021-07-27 06:47:36', 's', 5, 3),
(5, 'Alterar acesso', 'Alterar acesso', '2021-07-27 06:47:36', '2021-07-27 06:47:36', 's', 5, 3);


-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_status`
--

DROP TABLE IF EXISTS `tb_status`;
CREATE TABLE IF NOT EXISTS `tb_status` (
  `status_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_nm` varchar(60) NOT NULL,
  `status_des` varchar(255) NOT NULL,
  `data_add` datetime DEFAULT current_timestamp(),
  `data_up` datetime DEFAULT current_timestamp(),
  `ativo_fl` enum('s','n') DEFAULT 's',
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncar tabela antes do insert `tb_tipodeservico`
--

TRUNCATE TABLE `tb_status`;
--
-- Extraindo dados da tabela `tb_status`
--

INSERT INTO `tb_status` (`status_id`, `status_nm`, `status_des`, `data_add`, `data_up`, `ativo_fl`) VALUES
(1, 'Detectado e Registrado', 'Detectado e Registrado', '2021-07-27 06:29:19', '2021-07-27 06:29:19', 's'),
(2, 'Em Análise', 'Em Análise', '2021-07-27 06:29:20', '2021-07-27 06:29:20', 's'),
(3, 'Em Atendimento', 'Em Atendimento', '2021-07-27 06:29:20', '2021-07-27 06:29:20', 's'),
(4, 'Em Atendimento (Nível 2)', 'Em Atendimento (Nível 2)', '2021-07-27 06:29:20', '2021-07-27 06:29:20', 's'),
(5, 'Solucionado', 'Solucionado', '2021-07-27 06:29:20', '2021-07-27 06:29:20', 's'),
(6, 'Atendido', 'Atendido', '2021-07-27 06:29:20', '2021-07-27 06:29:20', 's'),
(7, 'Cancelado', 'Cancelado', '2021-07-27 06:29:20', '2021-07-27 06:29:20', 's'),
(8, 'Fechado', 'Fechado', '2021-07-27 06:29:20', '2021-07-27 06:29:20', 's');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_tipodeservico`
--

DROP TABLE IF EXISTS `tb_tipodeservico`;
CREATE TABLE IF NOT EXISTS `tb_tipodeservico` (
  `tipodeservico_id` int(11) NOT NULL AUTO_INCREMENT,
  `tipodeservico_nm` varchar(60) NOT NULL,
  `tipodeservico_des` varchar(255) NOT NULL,
  `data_add` datetime DEFAULT current_timestamp(),
  `data_up` datetime DEFAULT current_timestamp(),
  `ativo_fl` enum('s','n') DEFAULT 's',
  PRIMARY KEY (`tipodeservico_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncar tabela antes do insert `tb_tipodeservico`
--

TRUNCATE TABLE `tb_tipodeservico`;
--
-- Extraindo dados da tabela `tb_tipodeservico`
--

INSERT INTO `tb_tipodeservico` (`tipodeservico_id`, `tipodeservico_nm`, `tipodeservico_des`, `data_add`, `data_up`, `ativo_fl`) VALUES
(1, 'Mudança no Portifólio', 'Requisição de Mudança no Portifólio de Serviços ', '2021-07-27 06:29:19', '2021-07-27 06:29:19', 's'),
(2, 'Mudança no Serviço (Melhoria)', 'Requisição de Mudança no Serviço (Melhoria) ', '2021-07-27 06:29:20', '2021-07-27 06:29:20', 's'),
(3, 'Acesso de Usuário', 'Requisição de Acesso de Usuário', '2021-07-27 06:29:20', '2021-07-27 06:29:20', 's'),
(4, 'Atividades Operacionais', 'Requisição em Atividades Operacionais ', '2021-07-27 06:29:20', '2021-07-27 06:29:20', 's');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_tipodeic`
--

DROP TABLE IF EXISTS `tb_tipodeic`;
CREATE TABLE IF NOT EXISTS `tb_tipodeic` (
  `tipodeic_id` int(11) NOT NULL AUTO_INCREMENT,
  `tipodeic_nm` varchar(60) NOT NULL,
  `tipodeic_des` varchar(255) NOT NULL,
  `data_add` datetime DEFAULT current_timestamp(),
  `data_up` datetime DEFAULT current_timestamp(),
  `ativo_fl` enum('s','n') DEFAULT 's',
  PRIMARY KEY (`tipodeic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncar tabela antes do insert `tb_tipodeic`
--

TRUNCATE TABLE `tb_tipodeic`;
--
-- Extraindo dados da tabela `tb_tipodeic`
--

INSERT INTO `tb_tipodeic` (`tipodeic_id`, `tipodeic_nm`, `tipodeic_des`, `data_add`, `data_up`, `ativo_fl`) VALUES
(1, 'Servidor', 'Servidor', '2021-07-27 06:29:19', '2021-07-27 06:29:19', 's'),
(2, 'Notebook', 'Notebook', '2021-07-27 06:29:20', '2021-07-27 06:29:20', 's'),
(3, 'Roteador', 'Roteador', '2021-07-27 06:29:20', '2021-07-27 06:29:20', 's'),
(4, 'Impressora', 'Impressora', '2021-07-27 06:29:20', '2021-07-27 06:29:20', 's'),
(5, 'Webcam', 'Webcam', '2021-07-27 06:29:20', '2021-07-27 06:29:20', 's'),
(6, 'Scanner', 'Scanner', '2021-07-27 06:29:20', '2021-07-27 06:29:20', 's'),
(7, 'Monitor', 'Monitor', '2021-07-27 06:29:20', '2021-07-27 06:29:20', 's'),
(8, 'Projetor', 'Projetor', '2021-07-27 06:29:20', '2021-07-27 06:29:20', 's'),
(9, 'Sistema web', 'Sistema web', '2021-07-27 06:29:20', '2021-07-27 06:29:20', 's'),
(10, 'Sistema desktop', 'Sistema desktop', '2021-07-27 06:29:20', '2021-07-27 06:29:20', 's'),
(11, 'Estação de Trabalho', 'Estação de Trabalho', '2021-07-27 06:29:20', '2021-07-27 06:29:20', 's'),
(12, 'Software', 'Software', '2021-07-27 06:29:20', '2021-07-27 06:29:20', 's'),
(13, 'Software de Gestão', 'Software de Gestão', '2021-07-27 06:29:20', '2021-07-27 06:29:20', 's'),
(14, 'Aplicativo', 'Aplicativo', '2021-07-27 06:29:20', '2021-07-27 06:29:20', 's'),
(15, 'Banco de Dados', 'Banco de Dados', '2021-07-27 06:29:20', '2021-07-27 06:29:20', 's');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_usuario`
--

DROP TABLE IF EXISTS `tb_usuario`;
CREATE TABLE IF NOT EXISTS `tb_usuario` (
  `usuario_id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_nm` varchar(40) NOT NULL,
  `matricula` int(10) DEFAULT NULL,
  `cpf` int(11) DEFAULT NULL,
  `rg` int(15) DEFAULT NULL,
  `logindeusuario` varchar(40) DEFAULT NULL,
  `email` varchar(60) NOT NULL,
  `senha` varchar(100) DEFAULT NULL,
  `id_perfil` int(11) NOT NULL,
  `id_cargo` int(11) NOT NULL,
  `id_departamento` int(11) NOT NULL,
  `sala` varchar(20) DEFAULT NULL,
  `usuario_fone` varchar(20) DEFAULT NULL,
  `data_add` datetime DEFAULT current_timestamp(),
  `data_up` datetime DEFAULT current_timestamp(),
  `ativo_fl` enum('s','n') DEFAULT 's',
  PRIMARY KEY (`usuario_id`),
  UNIQUE KEY `email` (`email`),
  KEY `id_cargo` (`id_cargo`),
  KEY `id_perfil` (`id_perfil`),
  KEY `id_departamento` (`id_departamento`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

--
-- Truncar tabela antes do insert `tb_usuario`
--

TRUNCATE TABLE `tb_usuario`;
--
-- Extraindo dados da tabela `tb_usuario`
--

INSERT INTO `tb_usuario` (`usuario_id`, `usuario_nm`, `matricula`, `cpf`, `rg`, `logindeusuario`, `email`, `senha`, `id_perfil`, `id_cargo`, `id_departamento`, `sala`, `usuario_fone`, `data_add`, `data_up`, `ativo_fl`) VALUES
(1, 'André Rodrigues Ribeiro', NULL, NULL, NULL, NULL, 'a.tangy@gmail.com', '$2y$10$NhNuNxBmg.CjeNT6OR2Vye8mCLu95Z7GGUH6UaHrdw/tKGhpD7DOO', 2, 1, 5, '405', '21-988647305', '2021-07-21 17:39:22', '2021-07-25 23:45:57', 's'),
(11, 'Maria Eduarda', NULL, NULL, NULL, NULL, 'fasfasf@fasf2a.com', '123456', 3, 1, 4, '123', '131212412412', '2021-07-23 05:16:37', '2021-07-25 22:09:16', 's'),
(17, 'Teste Teste3', NULL, NULL, NULL, NULL, 'teste3@teste.com.br', '$2y$10$.PCJ4sZTTS7rgWHlSiN5ueDjuTXzpp1E61GfDuQpfrf7yU7g7i9lm', 3, 1, 6, '424', '21988455366', '2021-07-24 02:53:05', '2021-07-25 01:29:11', 's'),
(19, 'Paula Fernandez de Souza', NULL, NULL, NULL, NULL, 'paula.f@gmail.com', '$2y$10$ck6hwycY6NE3EQUS6DD8leDhbI83Qe8Djq2hMxXKs3ie7wjCACzIy', 5, 1, 1, '409', '2384483220', '2021-07-24 19:41:04', '2021-07-25 20:31:05', 's'),
(20, 'Carlos Antônio', NULL, NULL, NULL, NULL, 'c.antonio@gmail.com', '$2y$10$NoD98bIFaTPn.ef3AAv5gOzfpuN3t7d1NL2AFBX0vgjRjnhVLw4Wu', 1, 1, 4, '103', '2156633499', '2021-07-24 19:43:06', '2021-07-25 22:27:17', 'n'),
(21, 'Alexandre Nascimento', NULL, NULL, NULL, NULL, 'alexandre.nascimento@emerj.jus.br', '$2y$10$6c8I.p5drjnDECLunuLOdeI1odrkKvpvmmNClzAE2H8d7pKHaI4/6', 3, 1, 5, '403', '2138844561', '2021-07-24 19:46:07', '2021-07-25 23:47:15', 'n'),
(22, 'Willian Telles2', NULL, NULL, NULL, NULL, 'williamtelles@tjrj.jus.br', '$2y$10$da.4ixbLjhzxIxHXkUoB9.HWG5ZvcIBfxyo0puRJIie1mssItpvXa', 3, 1, 5, '405', '2133024509', '2021-07-24 19:48:06', '2021-07-24 20:56:52', 's'),
(23, 'Teste de Insert 023', NULL, NULL, NULL, NULL, 'testdeinsert03@gmail.com', '$2y$10$JjRUZOpqjJMCA199b6Jt.eFFTIB1jdp/1bfLqrRoajnKp68aD5Jwe', 5, 1, 5, '102', '11000000001', '2021-07-24 19:58:52', '2021-07-25 22:17:14', 's'),
(27, 'qweqweqweqeqweqwean ffafaf', NULL, NULL, NULL, NULL, 'qweqeqwewqweqwan@gmail.com', '$2y$10$d1589kpCEICceoQavOcTCO813FVHWCt.KnfKVjzxVHHOdLagDA4Ci', 4, 2, 5, '405', '21988647305', '2021-07-25 23:45:04', '2021-07-25 23:45:04', 's'),
(28, 'JULIANA RIGO', NULL, NULL, NULL, NULL, 'julianamoralrigo@hotmail.com', '$2y$10$CwTBO1VVnbmdx/f/XaLUMOZb0GoJsj3uh1Q4vtMGnkGIJRaNtDIC2', 6, 1, 5, '401', '21988647305', '2021-07-25 23:46:34', '2021-07-27 02:23:14', 's');

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `tb_atendimento`
--
ALTER TABLE `tb_atendimento`
  ADD CONSTRAINT `fk_itemdeconfiguracao` FOREIGN KEY (`id_itemdeconfiguracao`) REFERENCES `tb_itemdeconfiguracao` (`itemdeconfiguracao_id`),
  ADD CONSTRAINT `fk_servico` FOREIGN KEY (`id_servico`) REFERENCES `tb_servico` (`servico_id`);

--
-- Limitadores para a tabela `tb_chamado`
--
ALTER TABLE `tb_chamado`
  ADD CONSTRAINT `fk_atendimento` FOREIGN KEY (`id_atendimento`) REFERENCES `tb_atendimento` (`atendimento_id`),
  ADD CONSTRAINT `fk_status` FOREIGN KEY (`id_status`) REFERENCES `tb_status` (`status_id`),
  ADD CONSTRAINT `fk_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `tb_usuario` (`usuario_id`);

--
-- Limitadores para a tabela `tb_departamento`
--
ALTER TABLE `tb_departamento`
  ADD CONSTRAINT `tb_departamento_ibfk_1` FOREIGN KEY (`cod_dep_super`) REFERENCES `tb_departamento` (`departamento_id`);

--
-- Limitadores para a tabela `tb_itemdeconfiguracao`
--
ALTER TABLE `tb_itemdeconfiguracao`
  ADD CONSTRAINT `fk_tipodeic` FOREIGN KEY (`id_tipodeic`) REFERENCES `tb_tipodeic` (`tipodeic_id`);

--
-- Limitadores para a tabela `tb_servico`
--
ALTER TABLE `tb_servico`
  ADD CONSTRAINT `fk_departamento` FOREIGN KEY (`id_departamento`) REFERENCES `tb_departamento` (`departamento_id`),
  ADD CONSTRAINT `fk_tipodeservico` FOREIGN KEY (`id_tipodeservico`) REFERENCES `tb_tipodeservico` (`tipodeservico_id`);

--
-- Limitadores para a tabela `tb_usuario`
--
ALTER TABLE `tb_usuario`
  ADD CONSTRAINT `tb_usuario_ibfk_1` FOREIGN KEY (`id_cargo`) REFERENCES `tb_cargo` (`cargo_id`),
  ADD CONSTRAINT `tb_usuario_ibfk_2` FOREIGN KEY (`id_departamento`) REFERENCES `tb_departamento` (`departamento_id`),
  ADD CONSTRAINT `fk_perfil` FOREIGN KEY (`id_perfil`) REFERENCES `tb_perfil` (`perfil_id`);
SET FOREIGN_KEY_CHECKS=1;

INSERT INTO `tb_itemdeconfiguracao` (`itemdeconfiguracao_id`, `itemdeconfiguracao_nm`, `itemdeconfiguracao_des`, `data_add`, `data_up`, `ativo_fl`, `id_tipodeic`) VALUES
(1, 'Login de Rede', 'Login de Rede', '2021-07-27 21:28:58', '2021-07-27 21:28:58', 's', 5),
(2, 'Internet', 'Internet', '2021-07-27 21:28:58', '2021-07-27 21:28:58', 's', 5),
(3, 'Servidor Backup', 'Servidor Backup', '2021-07-27 21:28:58', '2021-07-27 21:28:58', 's', 5),
(4, 'SIEM Acadêmico', 'SIEM Acadêmico', '2021-07-27 21:28:58', '2021-07-27 21:28:58', 's', 5),
(5, 'SIEM Internet', 'SIEM Internet', '2021-07-27 21:28:59', '2021-07-27 21:28:59', 's', 5),
(6, 'SIEM Eventos', 'SIEM Eventos', '2021-07-27 21:28:59', '2021-07-27 21:28:59', 's', 5),
(7, 'SCPE', 'SCPE', '2021-07-27 21:28:59', '2021-07-27 21:28:59', 's', 5),
(8, 'SPGE', 'SPGE', '2021-07-27 21:28:59', '2021-07-27 21:28:59', 's', 5),
(9, 'SISLOGEM', 'SISLOGEM', '2021-07-27 21:28:59', '2021-07-27 21:28:59', 's', 5),
(10, 'SGEMERJ', 'SGEMERJ', '2021-07-27 21:28:59', '2021-07-27 21:28:59', 's', 5),
(11, 'SOF', 'SOF', '2021-07-27 21:28:59', '2021-07-27 21:28:59', 's', 5),
(12, 'SEI', 'SEI', '2021-07-27 21:28:59', '2021-07-27 21:28:59', 's', 5),
(13, 'E-mail Institucional', 'E-mail Institucional', '2021-07-27 21:28:59', '2021-07-27 21:28:59', 's', 5);

INSERT INTO `tb_atendimento` (`atendimento_id`, `id_servico`, `id_itemdeconfiguracao`, `data_add`, `data_up`) VALUES
(1, 4, 1, '2021-07-27 21:32:02', '2021-07-27 21:32:02'),
(2, 4, 2, '2021-07-27 21:32:02', '2021-07-27 21:32:02'),
(3, 4, 3, '2021-07-27 21:32:02', '2021-07-27 21:32:02'),
(4, 4, 4, '2021-07-27 21:32:02', '2021-07-27 21:32:02'),
(5, 4, 5, '2021-07-27 21:32:02', '2021-07-27 21:32:02'),
(6, 4, 6, '2021-07-27 21:32:02', '2021-07-27 21:32:02'),
(7, 4, 7, '2021-07-27 21:32:02', '2021-07-27 21:32:02'),
(8, 4, 8, '2021-07-27 21:32:02', '2021-07-27 21:32:02'),
(9, 4, 9, '2021-07-27 21:32:02', '2021-07-27 21:32:02'),
(10, 4, 10, '2021-07-27 21:32:02', '2021-07-27 21:32:02'),
(11, 4, 11, '2021-07-27 21:32:02', '2021-07-27 21:32:02'),
(12, 4, 12, '2021-07-27 21:32:02', '2021-07-27 21:32:02'),
(13, 4, 13, '2021-07-27 21:32:02', '2021-07-27 21:32:02');


COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
