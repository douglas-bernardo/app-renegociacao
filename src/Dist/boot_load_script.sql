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
INSERT INTO tipo_contato (nome) VALUES ('Telefone');
INSERT INTO tipo_contato (nome) VALUES ('E-mail');
INSERT INTO tipo_contato (nome) VALUES ('Whatsapp');
