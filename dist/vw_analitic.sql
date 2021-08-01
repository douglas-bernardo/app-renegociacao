-- CREATE OR REPLACE VIEW vw_analitic AS
SELECT
	n.id,
    -- dados de ocorrencia
	o.numero_ocorrencia, 
    o.dtocorrencia AS data_ocorrencia,
	date_format( o.dtocorrencia , '%Y') AS ano_sol,
    date_format( o.dtocorrencia , '%b-%Y') AS ciclo_ini,
    date_format( o.dtocorrencia , '%b') AS ciclo_ini_mes,
    date_format( o.dtocorrencia , '%c') AS ciclo_ini_num,
    o.idmotivots,
    o.motivo AS motivo_ts,
    o.nomeusuario_cadastro,
	o.idusuario_resp AS usuario_resp_ts_id,
    o.nomeusuario_resp AS usuario_resp_ts,
    o.status_ts,
    o.departamento,
    -- dados cliente / contrato
	o.nome_cliente,
    o.numeroprojeto,
    o.numerocontrato,
	p.nomeprojeto AS produto,
	o.valor_venda,
    -- dados da negociação
	tp.id AS tipo_solicitacao_id,
    tp.nome AS tipo_solicitacao,
    n.motivo_id,
    m.nome AS motivo,
    ori.id AS origem_id,
    ori.nome AS origem,
    n.usuario_id,
    u.primeiro_nome AS usuario_resp_negociacao, 
	n.situacao_id AS situacao_id,
    st.nome AS situacao,
    n.data_finalizacao,
    n.transferida,
    DAte_format( n.data_finalizacao , '%Y') AS ano_fin,
    DAte_format( n.data_finalizacao , '%b-%Y') AS ciclo_fin,
    DAte_format( n.data_finalizacao , '%c') AS ciclo_fim_num,
    n.reembolso,
    n.numero_pc,
    n.taxas_extras,
    IFnull( c.multa, 0) AS multa,
    n.valor_primeira_parcela,
    if( n.situacao_id = 6, n.valor_primeira_parcela, 0) AS caixa_retencao,
    if( n.situacao_id = 7, n.valor_primeira_parcela, 0) AS caixa_reversao,
    rev.projeto_novo,
    rev.contrato_novo,
    rev.valor_venda_novo,
    ret.valor_financiado AS valor_retido,
    if(n.situacao_id = 7, 
		o.valor_venda - rev.valor_venda_novo - n.taxas_extras,
        if (n.situacao_id = 2, o.valor_venda - (n.taxas_extras + c.multa), 0)) AS perda_financeira,
	if(n.situacao_id = 6, 
		ret.valor_financiado, 
        if(n.situacao_id = 7, rev.valor_venda_novo, 0)) AS faturamento
FROM negociacao n
LEFT JOIN ocorrencia o ON n.ocorrencia_id = o.id
LEFT JOIN usuario u ON u.id = n.usuario_id
LEFT JOIN tipo_solicitacao tp ON tp.id = n.tipo_solicitacao_id
LEFT JOIN motivo m ON  m.id = n.motivo_id
LEFT JOIN origem ori ON ori.id = n.origem_id
LEFT JOIN produto p ON p.idprojetots = o.idprojetots 
LEFT JOIN situacao st ON st.id = n.situacao_id
LEFT JOIN retencao ret ON ret.negociacao_id = n.id 
LEFT JOIN cancelamento c ON c.negociacao_id = n.id
LEFT JOIN(SELECT 
				r.negociacao_id,
				p.numeroprojeto  AS projeto_novo,
				r.numerocontrato AS contrato_novo,
				r.valor_venda    AS valor_venda_novo
			FROM 
				reversao r
				LEFT JOIN produto p ON p.id = r.produto_id  ) AS rev
				ON rev.negociacao_id = n.id