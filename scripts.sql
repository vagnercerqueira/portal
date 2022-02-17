
CREATE TABLE `base_projeto_acesso_grupo` (
  `id` int(11) NOT NULL,
  `id_grupo` int(11) NOT NULL,
  `id_aplicacao` int(11) NOT NULL,
  `perm_cadastrar` char(1) DEFAULT 'S',
  `perm_alterar` char(1) DEFAULT 'S',
  `perm_excluir` char(1) DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `base_projeto_acesso_usuario`
--

CREATE TABLE `base_projeto_acesso_usuario` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_aplicacao` int(11) NOT NULL,
  `perm_cadastrar` char(1) DEFAULT 'S',
  `perm_alterar` char(1) DEFAULT 'S',
  `perm_excluir` char(1) DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `base_projeto_aplicacoes`
--

CREATE TABLE `base_projeto_aplicacoes` (
  `id` int(11) NOT NULL,
  `id_pai` int(11) DEFAULT NULL,
  `nome` varchar(50) NOT NULL,
  `icone` varchar(50) DEFAULT NULL,
  `caminho` varchar(50) NOT NULL,
  `ordem` decimal(3,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `base_projeto_aplicacoes`
--

INSERT INTO `base_projeto_aplicacoes` (`id`, `id_pai`, `nome`, `icone`, `caminho`, `ordem`) VALUES
(1, NULL, 'Usuarios', 'fas fa-users', '>', 99),
(2, 18, 'Grupo', NULL, 'Usu/Usu004.php', 2),
(3, 1, 'Cadastro de Usuarios', NULL, 'usu/Usu001.php', 1),
(4, 18, 'Aplicacoes', NULL, 'Usu/Usu002.php', 4),
(5, 18, 'Parametro geral', NULL, 'Usu/Usu005.php', 5),
(6, 18, 'Acessos', NULL, 'Usu/Usu003.php', NULL),
(7, 1, 'Acesso por usuario', NULL, 'Usu/Usu006.php', 3),
(17, 18, 'Tipo contratação', NULL, 'Usu/Usu008.php', NULL),
(18, 1, 'Cadastros Basicos', NULL, '>', 3),
(19, 18, 'Equipe', NULL, 'Usu/Usu007.php', NULL),
(20, 26, 'Status de ativacoes', NULL, 'Ven/Ven001.php', 21),
(21, NULL, 'Vendas', NULL, '>', 1),
(22, 26, 'Turno instalação', NULL, 'Ven/Ven002.php', 21),
(23, 26, 'Cadastro Faturamento', NULL, 'Ven/Ven003.php', 6),
(24, 26, 'Cadastro Fibra', NULL, 'Ven/Ven004.php', 4),
(25, 26, 'Cadastro TV', NULL, 'Ven/Ven005.php', 5),
(26, 21, 'Cadastros basicos', NULL, '>', 3),
(27, 21, 'Vendas', NULL, 'Ven/Ven006.php', NULL),
(28, 21, 'Atualizações base', NULL, 'Ven/Ven007.php', 2),
(29, 26, 'Forma pagamento', NULL, 'Ven/Ven008.php', 20),
(30, 26, 'Dias de vencimento', NULL, 'Ven/Ven009.php', 19),
(31, 26, 'Cadastro bancos', NULL, 'Ven/Ven010.php', 2),
(32, 26, 'Combo Plano', NULL, 'Ven/Ven011.php', 11),
(33, 26, 'Setor tratamento', NULL, 'Ven/Ven012.php', 13),
(34, 26, 'UF Atuação', NULL, 'Ven/Ven013.php', 18),
(35, 37, 'DVF x CSV', NULL, 'Ven/Ven015.php', 15),
(36, 26, 'Mensagem Whatsapp', NULL, 'Ven/Ven014.php', 17),
(37, 26, 'Parametros Uplods', NULL, '>', 1),
(38, 37, 'BOV x CSV', NULL, 'Ven/Ven016.php', 2),
(39, 56, 'Linhas Pgto', NULL, 'Ven/Ven017.php', 1),
(40, 58, 'LINHA PGTO x CSV', NULL, 'Ven/Ven018.php', NULL),
(41, 37, 'Venda Lote', NULL, 'Ven/Ven019.php', NULL),
(42, 57, 'Mailling', NULL, 'Ven/Ven020.php', NULL),
(43, 46, 'Peso dias', NULL, 'Ven/Ven021.php', 2),
(44, 21, 'Envio de emails', NULL, 'Ven/Ven022.php', NULL),
(45, NULL, 'Metas', NULL, '>', 1),
(46, 45, 'Cadastro basicos', NULL, '>', 4),
(47, 46, 'Tipos de meta', NULL, 'Met/Met001.php', 4),
(48, 45, 'Meta supervisor', NULL, 'Met/Met002.php', 2),
(49, 45, 'Meta vendedor', NULL, 'Met/Met003.php', 1),
(50, NULL, 'Producao', NULL, '>', 3),
(52, 50, 'Supervisor', NULL, '>', NULL),
(53, 52, 'Supervisor - Mensal', NULL, 'Prod/Prod002.php', NULL),
(54, 50, 'Vendedor', NULL, '>', NULL),
(55, 54, 'Vendedor - Mensal', NULL, 'Prod/Prod003.php', NULL),
(56, NULL, 'Financeiro', NULL, '>', 4),
(57, NULL, 'Mailing', NULL, '>', 5),
(58, 56, 'Cadastro basicos', NULL, '>', 2),
(59, NULL, 'Dashboard', NULL, '>', 25),
(60, 61, 'Vendas x Instaladas', NULL, 'Graf/Graf001.php', NULL),
(61, 59, 'Produção', NULL, '>', NULL),
(62, 21, 'Consulta DFV', NULL, 'Ven/Ven023.php', 23),
(63, 26, 'Parametros CSV', NULL, 'Ven/Ven024.php', NULL),
(64, 21, 'Vendas Supervisor', NULL, 'Ven/Ven025.php', NULL),
(65, 57, 'Mailing(s)', NULL, 'Ven/Ven026.php', 2),
(66, 21, 'POS - INSTALACAO', NULL, 'Ven/Ven028.php', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `base_projeto_auditar_log`
--

CREATE TABLE `base_projeto_auditar_log` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `http_user_agent` varchar(200) NOT NULL,
  `id_aplicacao` int(11) DEFAULT NULL,
  `aplicacao` varchar(50) NOT NULL,
  `tb` varchar(50) NOT NULL,
  `acao` varchar(15) NOT NULL,
  `dt_cadastro` datetime NOT NULL DEFAULT current_timestamp(),
  `dados` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `base_projeto_grupo_usuario` (
  `id` int(11) NOT NULL,
  `descricao` varchar(20) NOT NULL,
  `home` varchar(50) DEFAULT 'home_default',
  `superusuario` char(1) DEFAULT 'N',
  `formsearch` char(1) DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `base_projeto_grupo_usuario`
--

INSERT INTO `base_projeto_grupo_usuario` (`id`, `descricao`, `home`, `superusuario`, `formsearch`) VALUES
(1, 'Super usuario', 'home_superusuario', 'S', 'S'),
(2, 'admin', 'home_default', 'N', 'S');

-- --------------------------------------------------------

--
-- Estrutura da tabela `base_projeto_parametro_sistema`
--

CREATE TABLE `base_projeto_parametro_sistema` (
  `id` int(11) NOT NULL,
  `email_suporte` varchar(100) NOT NULL,
  `envia_email_usuario` char(1) DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `base_projeto_parametro_sistema`
--

INSERT INTO `base_projeto_parametro_sistema` (`id`, `email_suporte`, `envia_email_usuario`) VALUES
(1, 'suporte@arkivar.net', 'N');

-- --------------------------------------------------------

--
-- Estrutura da tabela `base_projeto_senha_email`
--

CREATE TABLE `base_projeto_senha_email` (
  `id` int(11) NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario` int(11) NOT NULL,
  `senha_temp` varchar(100) NOT NULL,
  `status` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `base_projeto_usuarios`
--

CREATE TABLE `base_projeto_usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `grupo` int(11) DEFAULT NULL,
  `status` char(1) NOT NULL COMMENT 'A=ATIVO, I=INATIVO, D=DESLIGADO,C=AGUARDANDO CONFIRMACAO',
  `email` varchar(100) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `senha` varchar(100) NOT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `base_projeto_usuarios`
--

INSERT INTO `base_projeto_usuarios` (`id`, `nome`, `grupo`, `status`, `email`, `usuario`, `senha`, `foto`, `created_at`, `updated_at`) VALUES
(1, 'suporte', 1, 'A', 'vagner.cerqueira@live.com', 'suporte', '$2y$10$46UoLJSkPLMM3nB30C4lFOnS8SobxFN7vT97Hu7S/YmA3e.zcpuiG', 'suporte.jpg', '2020-08-11 14:15:28', '2020-10-02');


CREATE TABLE `acompanhamento_cliente` (
  `id` int(11) NOT NULL,
  `dt_update` datetime DEFAULT NULL,
  `edit_usuario_id` int(11) DEFAULT NULL,
  `num_os` varchar(15) NOT NULL,
  `zap_m_0` char(1) DEFAULT NULL,
  `zap_m_1` char(1) DEFAULT NULL,
  `zap_m_2` char(1) DEFAULT NULL,
  `zap_m_3` char(1) DEFAULT NULL,
  `zap_m_4` char(1) DEFAULT NULL,
  `ativo` char(1) DEFAULT 'N',
  `adimplente` char(1) DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `base_projeto_acesso_grupo`
--
ALTER TABLE `base_projeto_acesso_grupo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_grupo_2` (`id_grupo`,`id_aplicacao`),
  ADD KEY `id_grupo` (`id_grupo`),
  ADD KEY `id_aplicacao` (`id_aplicacao`);

--
-- Índices para tabela `base_projeto_acesso_usuario`
--
ALTER TABLE `base_projeto_acesso_usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`,`id_aplicacao`),
  ADD KEY `id_aplicacao` (`id_aplicacao`);

--
-- Índices para tabela `base_projeto_aplicacoes`
--
ALTER TABLE `base_projeto_aplicacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pai` (`id_pai`);

--
-- Índices para tabela `base_projeto_auditar_log`
--
ALTER TABLE `base_projeto_auditar_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Índices para tabela `base_projeto_grupo_usuario`
--
ALTER TABLE `base_projeto_grupo_usuario`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `base_projeto_parametro_sistema`
--
ALTER TABLE `base_projeto_parametro_sistema`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `base_projeto_senha_email`
--
ALTER TABLE `base_projeto_senha_email`
  ADD PRIMARY KEY (`id`),
  ADD KEY `base_projeto_senha_email_ibfk_1` (`usuario`);

--
-- Índices para tabela `base_projeto_usuarios`
--
ALTER TABLE `base_projeto_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD KEY `grupo` (`grupo`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `base_projeto_acesso_grupo`
--
ALTER TABLE `base_projeto_acesso_grupo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `base_projeto_acesso_usuario`
--
ALTER TABLE `base_projeto_acesso_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `base_projeto_aplicacoes`
--
ALTER TABLE `base_projeto_aplicacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `base_projeto_auditar_log`
--
ALTER TABLE `base_projeto_auditar_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de tabela `base_projeto_grupo_usuario`
--
ALTER TABLE `base_projeto_grupo_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `base_projeto_parametro_sistema`
--
ALTER TABLE `base_projeto_parametro_sistema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `base_projeto_senha_email`
--
ALTER TABLE `base_projeto_senha_email`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `base_projeto_usuarios`
--
ALTER TABLE `base_projeto_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `base_projeto_acesso_grupo`
--
ALTER TABLE `base_projeto_acesso_grupo`
  ADD CONSTRAINT `base_projeto_acesso_grupo_ibfk_1` FOREIGN KEY (`id_aplicacao`) REFERENCES `base_projeto_aplicacoes` (`id`),
  ADD CONSTRAINT `base_projeto_acesso_grupo_ibfk_2` FOREIGN KEY (`id_grupo`) REFERENCES `base_projeto_grupo_usuario` (`id`);

--
-- Limitadores para a tabela `base_projeto_acesso_usuario`
--
ALTER TABLE `base_projeto_acesso_usuario`
  ADD CONSTRAINT `base_projeto_acesso_usuario_ibfk_1` FOREIGN KEY (`id_aplicacao`) REFERENCES `base_projeto_aplicacoes` (`id`),
  ADD CONSTRAINT `base_projeto_acesso_usuario_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `base_projeto_usuarios` (`id`);

--
-- Limitadores para a tabela `base_projeto_auditar_log`
--
ALTER TABLE `base_projeto_auditar_log`
  ADD CONSTRAINT `base_projeto_auditar_log_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `base_projeto_usuarios` (`id`);

--
-- Limitadores para a tabela `base_projeto_senha_email`
--
ALTER TABLE `base_projeto_senha_email`
  ADD CONSTRAINT `base_projeto_senha_email_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `base_projeto_usuarios` (`id`);

--
-- Limitadores para a tabela `base_projeto_usuarios`
--
ALTER TABLE `base_projeto_usuarios`
  ADD CONSTRAINT `base_projeto_usuarios_ibfk_1` FOREIGN KEY (`grupo`) REFERENCES `base_projeto_grupo_usuario` (`id`);
COMMIT;

--
-- Índices para tabela `acompanhamento_cliente`
--
ALTER TABLE `acompanhamento_cliente`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `num_os` (`num_os`),
  ADD KEY `edit_usuario_id` (`edit_usuario_id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `acompanhamento_cliente`
--
ALTER TABLE `acompanhamento_cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;