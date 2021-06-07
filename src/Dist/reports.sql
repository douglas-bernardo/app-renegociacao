/*
SELECT 
	vw.situacao_id,
	vw.situacao AS tipo,
	sum(vw.valor_venda) AS valor_solicitado
FROM 
	vw_analitic vw
WHERE 
	vw.idusuario_resp = 972599
    and vw.ano_sol = 2021
    AND vw.situacao_id in (1, 2, 6, 7)
GROUP BY 1
*/

/*
SELECT
	ciclo_ini as cycle_start,
	round( (sum(faturamento) / sum(valor_venda)) * 100, 2) AS efficiency,
	round( (sum(perda_financeira) / sum(valor_venda)) * 100, 2) AS financial_loss
FROM
	vw_analitic
WHERE
	year(data_ocorrencia) = 2021
	AND (tipo_solicitacao_id = 2 OR tipo_solicitacao_id = 4)
	AND situacao_id in (1, 2, 6, 7)
	AND idusuario_resp = 972599
GROUP BY 1 order by ciclo_ini_num
*/

-- Solicitação de Cancelamento Mensal
/*
SELECT
	ciclo_ini as cycle_start,
    count(numero_ocorrencia) as total_recebido,
    primeiro_nome as negotiator_name,
    sum(valor_venda) as request_value,
    sum(faturamento) as profit,
    sum(perda_financeira) as financial_loss_value,
	round( (sum(faturamento) / sum(valor_venda)) * 100, 2) AS efficiency,
	round( (sum(perda_financeira) / sum(valor_venda)) * 100, 2) AS financial_loss
FROM
	vw_analitic
WHERE
	ano_sol = 2021
	AND (tipo_solicitacao_id = 2 OR tipo_solicitacao_id = 4)
	AND situacao_id in (1, 2, 6, 7)
	AND idusuario_resp = 972599
GROUP BY 1, ciclo_ini_num order by ciclo_ini_num
*/


-- Solicitação de Cancelamento Mensal 7 dias
-- SELECT
-- 	ciclo_fin as cycle_end,
-- 	usuario_resp_negociacao as negotiator_name,
-- 	sum(valor_venda) as request_value,
-- 	sum(faturamento) as profit,
-- 	round( (sum(faturamento) / sum(valor_venda)) * 100, 2) AS efficiency
-- FROM
-- 	vw_analitic
-- WHERE
-- 	ano_sol = 2021
-- 	AND (ano_fin = 2021 OR ano_fin is null)
-- 	AND tipo_solicitacao_id = 1
-- 	AND situacao_id in (1, 2, 6, 7)
-- 	AND id_usuario_resp_ts = 640054
-- GROUP BY ciclo_ini_num order by ciclo_ini_num

-- Faturamento Acumulado Ano
-- SELECT
-- 	ciclo_fin as cycle_end,
-- 	usuario_resp_negociacao as negotiator_name,
-- 	sum(valor_venda) as request_amount,
-- 	sum(valor_retido) as kept_amount,
-- 	sum(valor_venda_novo) as new_contract_value,
-- 	sum(perda_financeira) as financial_loss_amount,
-- 	sum(taxas_extras) as extra_rate,
-- 	sum(multa) as fine,
-- 	sum(reembolso) as refund
-- FROM
-- 	vw_analitic
-- WHERE
-- 	ano_sol = 2021
-- 	AND (ano_fin = 2021 OR ano_fin IS NULL)
-- 	AND tipo_solicitacao_id IN (2, 4)
-- 	AND situacao_id in (1, 2, 6, 7)
-- 	AND id_usuario_resp_ts = 640054
-- GROUP BY ciclo_fin 
-- ORDER BY 
-- 	CASE 
-- 		WHEN ciclo_fin IS NULL 
-- 			THEN 1 
--             ELSE 0 
-- 		END, ciclo_fin


-- Solicitações mensais resumo - MonthlyRequestsSummary
-- SELECT
-- 	ciclo_ini as cycle_start,
-- 	sum(valor_venda) as request_amount,
-- 	sum(faturamento) as profit,
-- 	sum(perda_financeira) as financial_loss_value,
-- 	sum(valor_primeira_parcela) as balance
-- FROM
-- 	vw_analitic
-- WHERE
-- 	ano_sol = 2021
-- 	AND tipo_solicitacao_id IN (2, 4)
-- 	AND situacao_id in (1, 2, 6, 7)
-- 	AND id_usuario_resp_ts = 640054
-- GROUP BY 1


-- Valores e percentual em aberto
SELECT
	n.usuario_id,
	DATE_FORMAT( o.dtocorrencia , '%Y') AS ano_sol,
    u.primeiro_nome AS usuario_resp,
	sum(o.valor_venda) AS valor_solicitado,
    vl_aberto.valor_em_aberto,
    round( (vl_aberto.valor_em_aberto / sum(o.valor_venda)) * 100, 2 ) AS percentual
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
					year(o.dtocorrencia) = 2021
					AND n.tipo_solicitacao_id IN (2, 4)
					AND n.situacao_id = 1
					AND n.usuario_id = 1
				GROUP BY n.usuario_id) AS vl_aberto ON vl_aberto.usuario_id = n.usuario_id
WHERE
	year(o.dtocorrencia) = 2021
	AND n.tipo_solicitacao_id IN (2, 4)
	AND n.situacao_id IN (1, 2, 6, 7)
	AND n.usuario_id = 1
GROUP BY n.usuario_id, ano_sol