SELECT usuario_resp_negociacao                               AS negociador,
       count(numero_ocorrencia)                              AS ocorrencias_recebidas,
       sum(valor_venda)                                      AS valor_solicitado,
       sum(faturamento)                                      AS valor_faturamento,
       sum(perda_financeira)                                 AS valor_perda_financeira,
       round((sum(faturamento) / sum(valor_venda)) * 100, 2) AS eficiencia_percentual
FROM vw_analitic
WHERE PARAM_ANO_SOL
  AND (ano_fin = 2021 OR ano_fin IS NULL)
  AND tipo_solicitacao_id = 1
  AND situacao_id IN (1, 2, 6, 7)
GROUP BY usuario_resp_negociacao