-- CREATE OR REPLACE VIEW vw_analitic AS
select
    -- dados de ocorrencia
	o.numero_ocorrencia, 
    o.dtocorrencia as data_ocorrencia,
	DATE_FORMAT( o.dtocorrencia , '%Y') as ano_sol,
    DATE_FORMAT( o.dtocorrencia , '%b-%Y') as ciclo_ini,
    DATE_FORMAT( o.dtocorrencia , '%b') as ciclo_ini_mes,
    DATE_FORMAT( o.dtocorrencia , '%c') as ciclo_ini_num,
    o.idmotivots,
    o.motivo as motivo_ts,
    o.nomeusuario_cadastro,
	o.idusuario_resp as id_usuario_resp_ts,
    o.nomeusuario_resp as usuario_resp_ts,
    o.status_ts,
    o.departamento,
    -- dados cliente / contrato
	o.nome_cliente,
    o.numeroprojeto,
    o.numerocontrato,
	p.nomeprojeto as produto,
	o.valor_venda,
    -- dados da negociação
    n.id,
	tp.id as tipo_solicitacao_id,
    tp.nome as tipo_solicitacao,
    m.nome as motivo,
    ori.id as origem_id,
    ori.nome as origem,
    n.usuario_id,
    u.primeiro_nome as usuario_resp_negociacao, 
	n.situacao_id as situacao_id,
    st.nome as situacao,
    n.data_finalizacao,
    DATE_FORMAT( n.data_finalizacao , '%Y') as ano_fin,
    DATE_FORMAT( n.data_finalizacao , '%b-%Y') as ciclo_fin,
    n.reembolso,
    n.numero_pc,
    n.taxas_extras,
    c.multa,
    n.valor_primeira_parcela,
    rev.projeto_novo,
    rev.contrato_novo,
    rev.valor_venda_novo,
    ret.valor_financiado as valor_retido,
    if(n.situacao_id = 7, 
		o.valor_venda - rev.valor_venda_novo - n.taxas_extras,
        if (n.situacao_id = 2, o.valor_venda - (n.taxas_extras + c.multa), 0)) as perda_financeira,
	if(n.situacao_id = 6, 
		ret.valor_financiado, 
        if(n.situacao_id = 7, rev.valor_venda_novo, 0)) as faturamento
from 
	negociacao n
    left join ocorrencia o on n.ocorrencia_id = o.id
    left join usuario u on u.id = n.usuario_id
	left join tipo_solicitacao tp on tp.id = n.tipo_solicitacao_id
    left join motivo m on  m.id = n.motivo_id
    left join origem ori on ori.id = n.origem_id
    left join produto p on p.idprojetots = o.idprojetots 
    left join situacao st on st.id = n.situacao_id
    left join retencao ret on ret.negociacao_id = n.id 
    left join cancelamento c on c.negociacao_id = n.id
    left join (	SELECT 
					r.negociacao_id,
					p.numeroprojeto as projeto_novo,
					r.numerocontrato as contrato_novo,
					r.valor_venda as valor_venda_novo
				FROM 
					reversao r
					left join produto p on p.id = r.projeto_id  ) as rev
                    on rev.negociacao_id = n.id