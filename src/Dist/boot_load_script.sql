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
INSERT INTO situacao (nome) VALUES ('Reclame Aqui 7 Dias');
INSERT INTO situacao (nome) VALUES ('Carência covid 30 dias');
INSERT INTO situacao (nome) VALUES ('Carência covid 60 dias');
INSERT INTO situacao (nome) VALUES ('Carência covid 90 dias');
INSERT INTO situacao (nome) VALUES ('Solicitação de Informação');

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
