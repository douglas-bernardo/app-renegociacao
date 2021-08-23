use db_renegociacao;
-- -----------------------------------------------------
-- Carga dados: usuario
-- -----------------------------------------------------
-- INSERT INTO usuario (primeiro_nome, nome, email, password, ts_usuario_id) 
-- values ('admin', 'admin', 'admin@beachpark.com.br', md5('123456'), 1);

-- INSERT INTO usuario (primeiro_nome, nome, email, password, ts_usuario_id) 
-- values ('Bianca', 'ANEZIA BIANCA RANGEL OLIVEIRA', 'biancaoliveira@beachpark.com.br', md5('123456'), 972599);

-- INSERT INTO usuario (primeiro_nome, nome, email, password, ts_usuario_id) 
-- values ('Anne', 'ANNE GRACIELLI DE SOUSA', 'annesousa@beachpark.com.br', md5('123456'), 640054);

-- INSERT INTO usuario (primeiro_nome, nome, email, password, ts_usuario_id) 
-- values ('Deise', 'DEISIANE BARBOSA DA SILVA', 'deisianesilva@beachpark.com.br', md5('123456'), 312448);

-- INSERT INTO usuario (primeiro_nome, nome, email, password, ts_usuario_id) 
-- values ('Carine', 'CARINE DE OLIVEIRA CARVALHO', 'carineoliveira@beachpark.com.br', md5('123456'), 231905);

-- -----------------------------------------------------
-- Carga dados: origem
-- -----------------------------------------------------
INSERT INTO origem (nome) VALUES ('Cobrança');
INSERT INTO origem (nome) VALUES ('CRC');
INSERT INTO origem (nome) VALUES ('Online Cobrança');
INSERT INTO origem (nome) VALUES ('Online CRC');
INSERT INTO origem (nome) VALUES ('PDD');
INSERT INTO origem (nome) VALUES ('PDD Online');
INSERT INTO origem (nome) VALUES ('Reclame Aqui');
INSERT INTO origem (nome) VALUES ('Reclame Aqui - 7 Dias');
INSERT INTO origem (nome) VALUES ('Assessoria');
INSERT INTO origem (nome) VALUES ('Renegociação');
INSERT INTO origem (nome) VALUES ('Vendas à Distância');

-- -----------------------------------------------------
-- Carga dados: situacao
-- -----------------------------------------------------
INSERT INTO situacao (nome) VALUES ('Aguardando Retorno');
INSERT INTO situacao (nome) VALUES ('Cancelado');
INSERT INTO situacao (nome) VALUES ('Cobrança');
INSERT INTO situacao (nome) VALUES ('Ocorrência Cancelada');
INSERT INTO situacao (nome) VALUES ('Processo Jurídico');
INSERT INTO situacao (nome) VALUES ('Retido');
INSERT INTO situacao (nome) VALUES ('Revertido');
INSERT INTO situacao (nome) VALUES ('Arquivo Morto');
INSERT INTO situacao (nome) VALUES ('Nova Venda');
INSERT INTO situacao (nome) VALUES ('Não Venda');
INSERT INTO situacao (nome) VALUES ('Upgrade');
INSERT INTO situacao (nome) VALUES ('Aquisição de Pontos');
INSERT INTO situacao (nome) VALUES ('Sem Aquisição');
INSERT INTO situacao (nome) VALUES ('Contrato Vencido');
INSERT INTO situacao (nome) VALUES ('Vendas à Distância');
INSERT INTO situacao (nome) VALUES ('Cancel. Vainkará');
INSERT INTO situacao (nome) VALUES ('Reclame-Aqui 7 Dias');
INSERT INTO situacao (nome) VALUES ('Carência covid 30d');
INSERT INTO situacao (nome) VALUES ('Carência covid 60d');
INSERT INTO situacao (nome) VALUES ('Carência covid 90d');
INSERT INTO situacao (nome) VALUES ('Sol. de Informação');

-- -----------------------------------------------------
-- Carga dados: tipo_solicitacao
-- -----------------------------------------------------
INSERT INTO tipo_solicitacao (nome) VALUES ('Cancelamento 7 Dias');
INSERT INTO tipo_solicitacao (nome) VALUES ('Cancelamento Pós 7 Dias');
INSERT INTO tipo_solicitacao (nome) VALUES ('Negociação PDD');
INSERT INTO tipo_solicitacao (nome) VALUES ('Sol. De Negociação');
INSERT INTO tipo_solicitacao (nome) VALUES ('Procedimento Cancelamento PDD');
INSERT INTO tipo_solicitacao (nome) VALUES ('Sol. de Informação');

-- -----------------------------------------------------
-- Carga dados: status_ocorrencia
-- -----------------------------------------------------
INSERT INTO status_ocorrencia (nome) VALUES ('Em Aberto');
INSERT INTO status_ocorrencia (nome) VALUES ('Negociação Registrada');
INSERT INTO status_ocorrencia (nome) VALUES ('Encerrada Sem Negociação');


-- -----------------------------------------------------
-- Carga dados: tipo_contato
-- -----------------------------------------------------
INSERT INTO tipo_contato (nome) VALUES ('E-mail');
INSERT INTO tipo_contato (nome) VALUES ('Inline');
INSERT INTO tipo_contato (nome) VALUES ('Notificação');
INSERT INTO tipo_contato (nome) VALUES ('Telefone');
INSERT INTO tipo_contato (nome) VALUES ('Whatsapp');
INSERT INTO tipo_contato (nome) VALUES ('Skype');

-- -----------------------------------------------------
-- Carga dados: motivo
-- -----------------------------------------------------
INSERT INTO motivo (nome) VALUES ('ACORDO EXTRAJUDICIAL');
INSERT INTO motivo (nome) VALUES ('ACORDO JUDICIAL');
INSERT INTO motivo (nome) VALUES ('AQUISIÇÃO');
INSERT INTO motivo (nome) VALUES ('ARREPEND. 07 DIAS - INDISPONIBILIDADE RCI');
INSERT INTO motivo (nome) VALUES ('ARREPEND. 7 DIAS - ATENDIMENTO CAC');
INSERT INTO motivo (nome) VALUES ('ARREPEND. 7 DIAS - ATENDIMENTO RCI');
INSERT INTO motivo (nome) VALUES ('ARREPEND. 7 DIAS - CONTRADIÇÃO VENDA X CONTRATO');
INSERT INTO motivo (nome) VALUES ('ARREPEND. 7 DIAS - CUSTO X BENEFÍCIO BP');
INSERT INTO motivo (nome) VALUES ('ARREPEND. 7 DIAS - CUSTO X BENEFÍCIO RCI');
INSERT INTO motivo (nome) VALUES ('ARREPEND. 7 DIAS - DESEMPREGO/DIVÓRCIO/SAÚDE/ÓBITO');
INSERT INTO motivo (nome) VALUES ('ARREPEND. 7 DIAS - FINANCEIRO');
INSERT INTO motivo (nome) VALUES ('ARREPEND. 7 DIAS - INSATISFAÇÃO BP');
INSERT INTO motivo (nome) VALUES ('ARREPEND. 7 DIAS - INSATISFAÇÃO OCEANI');
INSERT INTO motivo (nome) VALUES ('ARREPEND. 7 DIAS - INSATISFAÇÃO RCI');
INSERT INTO motivo (nome) VALUES ('ARREPEND. 7 DIAS - MOTIVOS PARTICULARES');
INSERT INTO motivo (nome) VALUES ('ARREPEND. 7 DIAS - NÃO INFORMADO');
INSERT INTO motivo (nome) VALUES ('ARREPEND. 7 DIAS - OCEANI');
INSERT INTO motivo (nome) VALUES ('ARREPEND. 7 DIAS - PERFIL DE FÉRIAS');
INSERT INTO motivo (nome) VALUES ('CONTRADIÇÃO VENDA X CONTRATO');
INSERT INTO motivo (nome) VALUES ('CUSTO X BENEFÍCIO BP');
INSERT INTO motivo (nome) VALUES ('CUSTO X BENEFÍCIO RCI');
INSERT INTO motivo (nome) VALUES ('DESEMPREGO/DIVÓRCIO/SAÚDE/ÓBITO');
INSERT INTO motivo (nome) VALUES ('ERRO OPERACIONAL');
INSERT INTO motivo (nome) VALUES ('FINANCEIRO');
INSERT INTO motivo (nome) VALUES ('INADIMPLENTE');
INSERT INTO motivo (nome) VALUES ('INDISPONIBILIDADE BP');
INSERT INTO motivo (nome) VALUES ('INDISPONIBILIDADE RCI');
INSERT INTO motivo (nome) VALUES ('INSATISFAÇÃO BP');
INSERT INTO motivo (nome) VALUES ('INSATISFAÇÃO CRC');
INSERT INTO motivo (nome) VALUES ('INSATISFAÇÃO DPTO FINANCEIRO');
INSERT INTO motivo (nome) VALUES ('INSATISFAÇÃO HOTELARIA');
INSERT INTO motivo (nome) VALUES ('INSATISFAÇÃO OCEANI');
INSERT INTO motivo (nome) VALUES ('INSATISFAÇÃO RCI');
INSERT INTO motivo (nome) VALUES ('MOTIVOS PARTICULARES');
INSERT INTO motivo (nome) VALUES ('NÃO INFORMADO');
INSERT INTO motivo (nome) VALUES ('PERFIL DE FÉRIAS');
INSERT INTO motivo (nome) VALUES ('RENOVAÇÃO');
INSERT INTO motivo (nome) VALUES ('INDICAÇÃO');
INSERT INTO motivo (nome) VALUES ('RECARGA');

-- -----------------------------------------------------
-- Carga dados: motivo_transferencia
-- -----------------------------------------------------
INSERT INTO motivo_transferencia (nome) VALUES ('Negociador Atual Em Férias');
INSERT INTO motivo_transferencia (nome) VALUES ('Negociador Atual Demitido');
INSERT INTO motivo_transferencia (nome) VALUES ('Solicitação do Cliente');
INSERT INTO motivo_transferencia (nome) VALUES ('Erro Operacional - Abertura da Ocorrência');
INSERT INTO motivo_transferencia (nome) VALUES ('Erro Operacional - Transferência Indevida');
INSERT INTO motivo_transferencia (nome) VALUES ('Outros Motivos');

-- -----------------------------------------------------
-- Carga dados: goal_type
-- -----------------------------------------------------
INSERT INTO goal_type (`name`) VALUES ('Eficiência Pós 7 Dias');
INSERT INTO goal_type (`name`) VALUES ('Eficiência 7 Dias');
INSERT INTO goal_type (`name`) VALUES ('Eficiência PDD');
INSERT INTO goal_type (`name`) VALUES ('Valores Em Aberto');
INSERT INTO goal_type (`name`) VALUES ('Perda Financeira');
INSERT INTO goal_type (`name`) VALUES ('Valores Em Aberto PDD');

-- -----------------------------------------------------
-- Carga dados: goal_type
-- -----------------------------------------------------
INSERT INTO `month` (`name`, `month_number`) VALUES ('janeiro', 1);
INSERT INTO `month` (`name`, `month_number`) VALUES ('fevereiro', 2);
INSERT INTO `month` (`name`, `month_number`) VALUES ('marco', 3);
INSERT INTO `month` (`name`, `month_number`) VALUES ('abril', 4);
INSERT INTO `month` (`name`, `month_number`) VALUES ('maio', 5);
INSERT INTO `month` (`name`, `month_number`) VALUES ('junho', 6);
INSERT INTO `month` (`name`, `month_number`) VALUES ('julho', 7);
INSERT INTO `month` (`name`, `month_number`) VALUES ('agosto', 8);
INSERT INTO `month` (`name`, `month_number`) VALUES ('setembro', 9);
INSERT INTO `month` (`name`, `month_number`) VALUES ('outubro', 10);
INSERT INTO `month` (`name`, `month_number`) VALUES ('novembro', 11);
INSERT INTO `month` (`name`, `month_number`) VALUES ('dezembro', 12);

-- -----------------------------------------------------
-- Carga dados: permission
-- -----------------------------------------------------
-- Settings
INSERT INTO permission (name, description, key_word) VALUES ('Configurações Ver', 'Permite a visualização das configurações do sistema', 'configuracoesVer');
INSERT INTO permission (name, description, key_word) VALUES ('Configurações Permissões Ver', 'Permite a visualização permissões cadastradas no sistema', 'configuracoesPermissoesVer');
INSERT INTO permission (name, description, key_word) VALUES ('Configurações Permissões Editar', 'Permite a edição permissões cadastradas no sistema', 'configuracoesPermissoesEditar');
INSERT INTO permission (name, description, key_word) VALUES ('Configurações Permissões Excluir', 'Permite a exclusão permissões cadastradas no sistema', 'configuracoesPermissoesExcluir');

INSERT INTO permission (name, description, key_word) VALUES ('Configurações Funções Ver', 'Permite a visualização dos cargos/funções cadastrados no sistema', 'configuracoesFuncoesVer');
INSERT INTO permission (name, description, key_word) VALUES ('Configurações Funções Editar', 'Permite a edição dos cargos/funções cadastrados no sistema', 'configuracoesFuncoesEditar');

INSERT INTO permission (name, description, key_word) VALUES ('Configurações Metas Criar', 'Permite a criação de uma nova meta no sistema', 'configuracoesMetasCriar');
INSERT INTO permission (name, description, key_word) VALUES ('Configurações Metas Editar', 'Permite a edição de uma meta já cadastrada no sistema', 'configuracoesMetasEditar');
INSERT INTO permission (name, description, key_word) VALUES ('Configurações Metas Ver', 'Permite a visualização das metas cadastradas no sistema', 'configuracoesMetasVer');

INSERT INTO permission (name, description, key_word) VALUES ('Configurações Usuários Ver', 'Permite a visualização da lista de usuários atuais cadastrados no sistema', 'configuracoesUsuariosVer');
INSERT INTO permission (name, description, key_word) VALUES ('Configurações Usuários Criar', 'Permite a criação de um novo usuário nas configurações do sistema', 'configuracoesUsuariosCriar');
INSERT INTO permission (name, description, key_word) VALUES ('Configurações Usuários Editar', 'Permite a edição de um usuário cadastrado no sistema', 'configuracoesUsuariosEditar');
INSERT INTO permission (name, description, key_word) VALUES ('Configurações Usuários Excluir', 'Permite a exclusão de um usuário cadastrado no sistema', 'configuracoesUsuariosExcluir');
INSERT INTO permission (name, description, key_word) VALUES ('Configurações Usuários Reset de Senha', 'Permite realizar o reset de senha de um usuário', 'configuracoesUsuariosResetDeSenha');

-- Operation: occurrences
INSERT INTO permission (name, description, key_word) VALUES ('Ocorrencias Ver', 'Permite visualizar as ocorrências cadastradas no sistema', 'ocorrenciasVer');
INSERT INTO permission (name, description, key_word) VALUES ('Ocorrencias Registrar Negociação', 'Permite registrar negociações para uma ou mais ocorrencias cadastradas no sistema', 'ocorrenciasRegistrarNegociacao');
INSERT INTO permission (name, description, key_word) VALUES ('Ocorrências Fechar Sem Negociação', 'Permite fechar uma ocorrência sem atribuir uma negociação a mesma', 'ocorrenciasFecharSemNegociacao');
INSERT INTO permission (name, description, key_word) VALUES ('Ocorrencias Filtrar Por Resp', 'Permite realizar a filtragem das ocorrências de acordo com o usuário responsável', 'ocorrenciasFiltrarPorResp');

-- Operation: negotiations
INSERT INTO permission (name, description, key_word) VALUES ('Negociações Ver', 'Permite visualizar as negociações cadastradas no sistema', 'negociacoesVer');
INSERT INTO permission (name, description, key_word) VALUES ('Negociações Excluir', 'Permite excluir uma negociação cadastrada no sistema', 'negociacaoExcluir');
INSERT INTO permission (name, description, key_word) VALUES ('Negociações Editar', 'Permite editar uma negociação cadastrada no sistema', 'negociacaoEditar');
INSERT INTO permission (name, description, key_word) VALUES ('Negociações Transferir', 'Permite transferir uma negociação entre consultores', 'negociacaoTransferir');
INSERT INTO permission (name, description, key_word) VALUES ('Negociações Restaurar', 'Permite restaurar uma negociação como aguardando retorno', 'negociacaoRestaurar');
INSERT INTO permission (name, description, key_word) VALUES ('Negociações Filtrar Por Resp', 'Permite realizar a filtragem das negociações de acordo com o usuário responsável', 'negociacoesFiltrarPorResp');

INSERT INTO permission (name, description, key_word) VALUES ('Negociações Finalizar Cancelamento', 'Permite finalizar uma negociação como cancelamento', 'negociacaoFinalizarCancelamento');
INSERT INTO permission (name, description, key_word) VALUES ('Negociações Finalizar Retenção', 'Permite finalizar uma negociação como retenção', 'negociacaoFinalizarRetencao');
INSERT INTO permission (name, description, key_word) VALUES ('Negociações Finalizar Reversão', 'Permite finalizar uma negociação como reversão', 'negociacaoFinalizarReversao');
INSERT INTO permission (name, description, key_word) VALUES ('Negociações Finalizar Outros', 'Permite finalizar uma negociação como outros', 'negociacaoFinalizarOutros');