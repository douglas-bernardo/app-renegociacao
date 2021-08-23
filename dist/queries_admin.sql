-- Eficiência Acumulada

SELECT
	ano_sol,
	usuario_resp_negociacao as negociador,
	count(numero_ocorrencia) as ocorrencias_recebidas,
	sum(valor_venda) as valor_solicitado,
	sum(faturamento) as valor_faturamento,
	sum(perda_financeira) as valor_perda_financeira,
	round( (sum(faturamento) / sum(valor_venda)) * 100, 2) AS eficiencia_percentual,
	round( (sum(perda_financeira) / sum(valor_venda)) * 100, 2) AS perda_financeira_percentual,
    goal.target as meta
FROM
	vw_analitic
LEFT JOIN (SELECT 
				g.id,
				g.goal_type_id,
				g.current_year,
				g.active,
				gm.target,
				gm.month_number
			FROM
				goal g
					LEFT JOIN
				goal_type gt ON g.goal_type_id = gt.id
					LEFT JOIN
				goal_month gm ON gm.goal_id = g.id
			WHERE
				gm.month_number = date_format( now() , '%c') AND 
				g.current_year = 2021) AS goal ON goal.current_year = vw_analitic.ano_sol
WHERE
	ano_sol = 2021
	AND tipo_solicitacao_id IN (2, 4)
	AND situacao_id in (1, 2, 6, 7)
GROUP BY 
	usuario_resp_negociacao, ano_sol;


-- Eficiência 7 Dias Acumulada
/*
SELECT
	usuario_resp_negociacao as negociador,
    count(numero_ocorrencia) as ocorrencias_recebidas,
	sum(valor_venda) as valor_solicitado,
	sum(faturamento) as valor_faturamento,
    sum(perda_financeira) as valor_perda_financeira,
	round( (sum(faturamento) / sum(valor_venda)) * 100, 2) AS eficiencia_percentual
FROM
	vw_analitic
WHERE
	ano_sol = 2021
	AND (ano_fin = 2021 or ano_fin is null)
	AND tipo_solicitacao_id = 1
	AND situacao_id in (1, 2, 6, 7)
	-- AND usuario_id = {$user['uid']}
GROUP BY 
	usuario_resp_negociacao
*/    

-- Percentual em aberto
/*
SELECT
    n.usuario_id,
    DATE_FORMAT( o.dtocorrencia , '%Y') AS ano_sol,
    u.primeiro_nome AS usuario_resp,
    sum(o.valor_venda) AS valor_solicitado,
    IFnull(vl_aberto.valor_em_aberto, 0) AS valor_em_aberto,
    IFnull(round((vl_aberto.valor_em_aberto / sum(o.valor_venda)) * 100, 2 ), 0) AS percentual
FROM
    negociacao n
        LEFT JOIN ocorrencia o ON n.ocorrencia_id = o.id
        LEFT JOIN usuario u ON n.usuario_id = u.id
        LEFT JOIN (SELECT
                       n.usuario_id,
                       sum(o.valor_venda) AS valor_em_aberto
                   FROM
                       negociacao n
                           LEFT JOIN ocorrencia o ON n.ocorrencia_id = o.id
                           LEFT JOIN usuario u ON n.usuario_id = u.id
                   WHERE
                         DATE_FORMAT( o.dtocorrencia , '%Y') = 2021
                     AND n.tipo_solicitacao_id IN (2, 4)
                     AND n.situacao_id = 1
                     AND 1 = 1
                   GROUP BY n.usuario_id) AS vl_aberto ON vl_aberto.usuario_id = n.usuario_id
WHERE
      DATE_FORMAT( o.dtocorrencia , '%Y') = 2021
  AND n.tipo_solicitacao_id IN (2, 4)
  AND n.situacao_id IN (1, 2, 6, 7)
  AND 1 = 1
GROUP BY n.usuario_id, ano_sol
*/

-- Caixa Retenção/Reversão
/*
SELECT
	usuario_resp_negociacao as negociadora,
	sum(valor_venda) as valor_solicitado,
	IFnull(sum(valor_retido), 0) as valor_retido,
	sum(caixa_retencao) as caixa_retencao,
    IFnull(sum(valor_venda_novo), 0) as valor_revertido,
    sum(caixa_reversao) as caixa_reversao
FROM
	vw_analitic
WHERE
	ano_sol = 2021
	AND tipo_solicitacao_id IN (2, 4)
	AND situacao_id in (1, 2, 6, 7)
	-- AND usuario_id = {$user['uid']}
GROUP BY usuario_resp_negociacao;
*/

-- Eficiência Retenção Acumulada
/*
SELECT
	usuario_resp_negociacao as negociadora,
    count(numero_ocorrencia) as ocorrencias_recebidas,
	sum(valor_venda) as valor_solicitado,
	sum(valor_retido) as valor_retido,
    round( (sum(valor_retido) / sum(valor_venda)) * 100, 2) AS eficiencia_retencao_percentual
FROM
	vw_analitic
WHERE
	ano_sol = 2021
	AND tipo_solicitacao_id IN (2, 4)
	AND situacao_id in (1, 2, 6, 7)
	-- AND usuario_id = {$user['uid']}
GROUP BY usuario_resp_negociacao
*/

-- metas

SELECT 
    g.id,
    g.goal_type_id,
    g.current_year,
    g.active,
    gm.target,
    gm.month_number
FROM
    goal g
        LEFT JOIN
    goal_type gt ON g.goal_type_id = gt.id
        LEFT JOIN
    goal_month gm ON gm.goal_id = g.id
WHERE
    gm.month_number = date_format( now() , '%c') AND 
    g.current_year = date_format( now() , '%Y');
